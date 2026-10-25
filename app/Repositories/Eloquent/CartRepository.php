<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;

class CartRepository extends FilterableRepository implements CartRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['customer_id' => 'customer_id', 'status' => 'status'];

    /**
     * @var list<string>
     */
    protected array $searchable = [];

    /**
     * @var list<string>
     */
    protected array $with = ['customer:id,name,email'];

    /**
     * @var list<string>
     */
    protected array $withCount = ['items'];

    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }
}
