<?php

namespace Tests\Feature\Api\V1\Catalog;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        Product::query()->create($this->productPayload([
            'name' => 'Alpha Phone',
            'slug' => 'alpha-phone',
            'sku' => 'SKU-001',
        ]));

        Product::query()->create($this->productPayload([
            'name' => 'Beta Phone',
            'slug' => 'beta-phone',
            'sku' => 'SKU-002',
        ]));

        $this->getJson('/api/v1/products')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'Beta Phone')
            ->assertJsonPath('meta.per_page', 15);
    }

    public function test_can_filter_products_by_search_and_status(): void
    {
        Product::query()->create($this->productPayload([
            'name' => 'Visible Phone',
            'slug' => 'visible-phone',
            'sku' => 'SKU-010',
        ]));

        Product::query()->create($this->productPayload([
            'name' => 'Hidden Phone',
            'slug' => 'hidden-phone',
            'sku' => 'SKU-011',
            'status' => 'inactive',
        ]));

        $this->getJson('/api/v1/products?search=Visible&status=active')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sku', 'SKU-010');
    }

    public function test_can_create_a_product(): void
    {
        $this->postJson('/api/v1/products', $this->productPayload())
            ->assertCreated()
            ->assertJsonPath('data.sku', 'SKU-100')
            ->assertJsonPath('data.price', 99.99);

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-100',
            'slug' => 'test-product',
        ]);
    }

    public function test_product_requires_valid_and_unique_identifiers(): void
    {
        Product::query()->create($this->productPayload());

        $this->postJson('/api/v1/products', $this->productPayload([
            'slug' => 'another-product',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);

        $this->postJson('/api/v1/products', $this->productPayload([
            'sku' => 'SKU-101',
            'status' => 'not-a-status',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_can_update_a_product(): void
    {
        $product = Product::query()->create($this->productPayload());

        $this->patchJson("/api/v1/products/{$product->id}", [
            'price' => 149.99,
            'is_featured' => true,
        ])
            ->assertOk()
            ->assertJsonPath('data.price', 149.99)
            ->assertJsonPath('data.is_featured', true);
    }

    public function test_can_delete_a_product(): void
    {
        $product = Product::query()->create($this->productPayload());

        $this->deleteJson("/api/v1/products/{$product->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_returns_a_consistent_error_for_a_missing_product(): void
    {
        $this->getJson('/api/v1/products/999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Product not found.');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function productPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'SKU-100',
            'description' => 'A product used by the API test suite.',
            'price' => 99.99,
            'original_price' => 129.99,
            'stock' => 10,
            'status' => 'active',
            'is_featured' => false,
            'is_bestseller' => false,
            'is_flash_sale' => false,
            'is_active' => true,
        ], $overrides);
    }
}
