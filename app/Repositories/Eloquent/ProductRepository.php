<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query()
            ->with(['category:id,name,slug', 'brand:id,name,slug', 'images'])
            ->withCount('reviews');

        $query
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->whereLike('name', "%{$search}%")
                        ->orWhereLike('sku', "%{$search}%")
                        ->orWhereLike('slug', "%{$search}%");
                });
            })
            ->when($filters['category_id'] ?? null, fn (Builder $query, mixed $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['brand_id'] ?? null, fn (Builder $query, mixed $brandId) => $query->where('brand_id', $brandId))
            ->when($filters['status'] ?? null, fn (Builder $query, mixed $status) => $query->where('status', $status))
            ->when(isset($filters['is_active']), fn (Builder $query) => $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)))
            ->when(isset($filters['is_featured']), fn (Builder $query) => $query->where('is_featured', filter_var($filters['is_featured'], FILTER_VALIDATE_BOOLEAN)))
            ->when($filters['min_price'] ?? null, fn (Builder $query, mixed $minPrice) => $query->where('price', '>=', $minPrice))
            ->when($filters['max_price'] ?? null, fn (Builder $query, mixed $maxPrice) => $query->where('price', '<=', $maxPrice));

        match ($filters['sort'] ?? 'latest') {
            'oldest' => $query->oldest('id'),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            default => $query->latest('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }
}
