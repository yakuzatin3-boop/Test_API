<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class InventoryService extends CrudService
{
    public function __construct(InventoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['product:id,name,slug', 'variant:id,product_id,sku,size,color']);
    }
}
