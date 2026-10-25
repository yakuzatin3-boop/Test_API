<?php

namespace App\Services\Sales;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItemService extends CrudService
{
    public function __construct(OrderItemRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['order:id,order_number,status', 'product:id,name,slug', 'variant:id,product_id,sku,size,color']);
    }

    public function create(array $attributes): Model
    {
        return DB::transaction(function () use ($attributes) {
            $attributes = $this->fillSnapshot($attributes);
            $attributes['subtotal'] = (int) $attributes['quantity'] * (float) $attributes['price'];

            /** @var OrderItem $item */
            $item = parent::create($attributes);

            $this->syncOrder($item->order_id);

            return $item;
        });
    }

    public function update(Model $model, array $attributes): Model
    {
        return DB::transaction(function () use ($model, $attributes) {
            $attributes = array_merge([
                'order_id' => $model->order_id,
                'product_id' => $model->product_id,
                'variant_id' => $model->variant_id,
                'product_name' => $model->product_name,
                'sku' => $model->sku,
                'quantity' => $model->quantity,
                'price' => $model->price,
            ], $attributes);

            $attributes = $this->fillSnapshot($attributes);
            $attributes['subtotal'] = (int) $attributes['quantity'] * (float) $attributes['price'];

            /** @var OrderItem $item */
            $item = parent::update($model, $attributes);

            $this->syncOrder($item->order_id);

            return $item;
        });
    }

    public function delete(Model $model): bool
    {
        return DB::transaction(function () use ($model) {
            $orderId = $model->order_id;
            $deleted = parent::delete($model);

            $this->syncOrder($orderId);

            return $deleted;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function fillSnapshot(array $attributes): array
    {
        if (! empty($attributes['variant_id'])) {
            $variant = ProductVariant::query()->find($attributes['variant_id']);
            $attributes['sku'] ??= $variant?->sku;
        }

        if (! empty($attributes['product_id'])) {
            $product = Product::query()->find($attributes['product_id']);
            $attributes['product_name'] ??= $product?->name;
            $attributes['sku'] ??= $product?->sku;
        }

        $attributes['product_name'] ??= 'Item';

        return $attributes;
    }

    private function syncOrder(int|string $orderId): void
    {
        $order = Order::query()->find($orderId);

        if ($order !== null) {
            app(OrderService::class)->recalculate($order);
        }
    }
}
