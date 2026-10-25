<?php

namespace App\Services\Catalog;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->products->paginateFiltered($filters, $perPage);
    }

    public function detail(Product $product): Product
    {
        return $product
            ->load(['category', 'brand', 'images'])
            ->loadCount('reviews');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Product
    {
        /** @var Product $product */
        $product = $this->products->create($attributes);

        return $product;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Product $product, array $attributes): Product
    {
        /** @var Product $updated */
        $updated = $this->products->update($product->getKey(), $attributes);

        return $updated;
    }

    public function delete(Product $product): bool
    {
        return $this->products->delete($product->getKey());
    }
}
