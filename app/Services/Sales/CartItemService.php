<?php

namespace App\Services\Sales;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\CartItemRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CartItemService extends CrudService
{
    public function __construct(CartItemRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['cart:id,customer_id,status', 'product:id,name,slug', 'variant:id,product_id,sku,size,color']);
    }

    public function create(array $attributes): Model
    {
        return DB::transaction(function () use ($attributes) {
            $attributes['price'] = $this->resolvePrice($attributes);

            $existing = CartItem::query()
                ->where('cart_id', $attributes['cart_id'])
                ->where('product_id', $attributes['product_id'])
                ->when(
                    ! empty($attributes['variant_id']),
                    fn ($query) => $query->where('variant_id', $attributes['variant_id']),
                    fn ($query) => $query->whereNull('variant_id'),
                )
                ->lockForUpdate()
                ->first();

            if ($existing !== null) {
                $existing->quantity += (int) ($attributes['quantity'] ?? 1);
                $existing->price = $attributes['price'];
                $existing->save();

                return $existing->refresh();
            }

            return parent::create($attributes);
        });
    }

    public function update(Model $model, array $attributes): Model
    {
        if (array_key_exists('price', $attributes) || array_key_exists('product_id', $attributes) || array_key_exists('variant_id', $attributes)) {
            $attributes['price'] = $this->resolvePrice(array_merge([
                'product_id' => $model->product_id,
                'variant_id' => $model->variant_id,
                'price' => $model->price,
            ], $attributes));
        }

        return parent::update($model, $attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function resolvePrice(array $attributes): float
    {
        if (isset($attributes['price'])) {
            return (float) $attributes['price'];
        }

        if (! empty($attributes['variant_id'])) {
            $price = ProductVariant::query()->whereKey($attributes['variant_id'])->value('price');

            if ($price !== null) {
                return (float) $price;
            }
        }

        return (float) Product::query()->whereKey($attributes['product_id'])->value('price');
    }
}
