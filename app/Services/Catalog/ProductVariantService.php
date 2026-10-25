<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\ProductVariantRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class ProductVariantService extends CrudService
{
    public function __construct(ProductVariantRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['product:id,name,slug']);
    }
}
