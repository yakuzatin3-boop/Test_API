<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderItem;
use App\Repositories\Contracts\OrderItemRepositoryInterface;

class OrderItemRepository extends FilterableRepository implements OrderItemRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['order_id' => 'order_id', 'product_id' => 'product_id', 'variant_id' => 'variant_id'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['product_name', 'sku'];

    /**
     * @var list<string>
     */
    protected array $with = ['order:id,order_number,status', 'product:id,name,slug', 'variant:id,product_id,sku,size,color'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(OrderItem $model)
    {
        parent::__construct($model);
    }
}
