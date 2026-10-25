<?php

namespace App\Services\Sales;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class CartService extends CrudService
{
    public function __construct(CartRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['customer:id,name,email', 'items.product:id,name,slug', 'items.variant:id,product_id,sku,size,color'])->loadCount(['items']);
    }
}
