<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository extends FilterableRepository implements OrderRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['customer_id' => 'customer_id', 'status' => 'status'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['order_number'];

    /**
     * @var list<string>
     */
    protected array $with = ['customer:id,name,email'];

    /**
     * @var list<string>
     */
    protected array $withCount = ['items', 'payments', 'shipments'];

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
