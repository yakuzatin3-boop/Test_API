<?php

namespace App\Services\Catalog;

use App\Models\ProductImage;
use App\Repositories\Contracts\ProductImageRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductImageService extends CrudService
{
    public function __construct(ProductImageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['product:id,name,slug']);
    }

    public function create(array $attributes): Model
    {
        return DB::transaction(function () use ($attributes) {
            if (! empty($attributes['is_thumbnail'])) {
                ProductImage::query()
                    ->where('product_id', $attributes['product_id'])
                    ->update(['is_thumbnail' => false]);
            }

            return parent::create($attributes);
        });
    }

    public function update(Model $model, array $attributes): Model
    {
        return DB::transaction(function () use ($model, $attributes) {
            if (! empty($attributes['is_thumbnail'])) {
                ProductImage::query()
                    ->where('product_id', $model->product_id)
                    ->whereKeyNot($model->getKey())
                    ->update(['is_thumbnail' => false]);
            }

            return parent::update($model, $attributes);
        });
    }
}
