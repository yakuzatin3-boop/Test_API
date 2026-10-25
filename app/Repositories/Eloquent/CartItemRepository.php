<?php

namespace App\Repositories\Eloquent;

use App\Models\CartItem;
use App\Repositories\Contracts\CartItemRepositoryInterface;

class CartItemRepository extends FilterableRepository implements CartItemRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['cart_id' => 'cart_id', 'product_id' => 'product_id', 'variant_id' => 'variant_id'];

    /**
     * @var list<string>
     */
    protected array $searchable = [];

    /**
     * @var list<string>
     */
    protected array $with = ['product:id,name,slug', 'variant:id,product_id,sku,size,color'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(CartItem $model)
    {
        parent::__construct($model);
    }
}
