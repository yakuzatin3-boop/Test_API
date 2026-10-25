<?php

namespace Tests\Feature\Api\V1\Sales;

use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesFlowTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): Customer
    {
        return Customer::query()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);
    }

    private function product(float $price = 50): Product
    {
        return Product::query()->create([
            'name' => 'T-Shirt',
            'slug' => 't-shirt-'.uniqid(),
            'sku' => 'SKU-'.uniqid(),
            'price' => $price,
        ]);
    }

    public function test_cart_items_merge_and_resolve_price(): void
    {
        $customer = $this->customer();
        $product = $this->product(50);

        $cart = $this->postJson('/api/v1/carts', [
            'customer_id' => $customer->id,
        ])->assertCreated()->json('data');

        $first = $this->postJson('/api/v1/cart-items', [
            'cart_id' => $cart['id'],
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertCreated()
            ->assertJsonPath('data.price', 50)
            ->assertJsonPath('data.quantity', 1)
            ->json('data');

        $second = $this->postJson('/api/v1/cart-items', [
            'cart_id' => $cart['id'],
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertCreated()
            ->assertJsonPath('data.quantity', 3)
            ->assertJsonPath('data.id', $first['id'])
            ->json('data');

        $this->assertSame($first['id'], $second['id']);
        $this->assertSame(1, CartItem::query()->count());

        $this->getJson("/api/v1/carts/{$cart['id']}")
            ->assertOk()
            ->assertJsonPath('data.items_count', 1)
            ->assertJsonPath('data.items_total', 150);
    }

    public function test_cart_item_uses_variant_price(): void
    {
        $customer = $this->customer();
        $product = $this->product(50);

        $variant = ProductVariant::query()->create([
            'product_id' => $product->id,
            'sku' => 'SKU-VAR-1',
            'price' => 65,
            'stock' => 5,
        ]);

        $cart = $this->postJson('/api/v1/carts', [
            'customer_id' => $customer->id,
        ])->json('data');

        $this->postJson('/api/v1/cart-items', [
            'cart_id' => $cart['id'],
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ])->assertCreated()
            ->assertJsonPath('data.price', 65);
    }

    public function test_order_totals_follow_order_items(): void
    {
        $customer = $this->customer();
        $product = $this->product(30);

        $order = $this->postJson('/api/v1/orders', [
            'customer_id' => $customer->id,
            'shipping_fee' => 10,
            'discount' => 5,
            'shipping_address' => ['city' => 'Dhaka'],
        ])->assertCreated()
            ->assertJsonPath('data.subtotal', 0)
            ->assertJsonPath('data.total', 5)
            ->json('data');

        $this->assertNotEmpty($order['order_number']);
        $this->assertNotEmpty($order['placed_at']);

        $item = $this->postJson('/api/v1/order-items', [
            'order_id' => $order['id'],
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 30,
        ])->assertCreated()
            ->assertJsonPath('data.product_name', 'T-Shirt')
            ->assertJsonPath('data.subtotal', 60)
            ->json('data');

        $this->getJson("/api/v1/orders/{$order['id']}")
            ->assertOk()
            ->assertJsonPath('data.subtotal', 60)
            ->assertJsonPath('data.total', 65);

        $this->patchJson("/api/v1/orders/{$order['id']}", [
            'shipping_fee' => 20,
        ])->assertOk()
            ->assertJsonPath('data.total', 75);

        $this->deleteJson("/api/v1/order-items/{$item['id']}")
            ->assertNoContent();

        $this->getJson("/api/v1/orders/{$order['id']}")
            ->assertOk()
            ->assertJsonPath('data.subtotal', 0)
            ->assertJsonPath('data.total', 15);
    }

    public function test_payment_and_shipment_side_effects(): void
    {
        $customer = $this->customer();

        $order = $this->postJson('/api/v1/orders', [
            'customer_id' => $customer->id,
            'shipping_address' => ['city' => 'Dhaka'],
        ])->json('data');

        $this->postJson('/api/v1/payments', [
            'order_id' => $order['id'],
            'payment_method' => 'card',
            'amount' => 100,
            'status' => 'paid',
        ])->assertCreated()
            ->assertJsonPath('data.status', 'paid')
            ->assertJsonPath('data.paid_at', fn ($value) => $value !== null);

        $shipment = $this->postJson('/api/v1/shipments', [
            'order_id' => $order['id'],
            'carrier' => 'DHL',
            'status' => 'shipped',
        ])->assertCreated()
            ->assertJsonPath('data.shipped_at', fn ($value) => $value !== null)
            ->assertJsonPath('data.shipping_address.city', 'Dhaka')
            ->json('data');

        $this->patchJson("/api/v1/shipments/{$shipment['id']}", [
            'status' => 'delivered',
        ])->assertOk()
            ->assertJsonPath('data.delivered_at', fn ($value) => $value !== null);
    }
}
