<?php

namespace App\Repositories\Eloquent;

use App\Models\Wishlist;
use App\Repositories\Contracts\WishlistRepositoryInterface;

class WishlistRepository extends FilterableRepository implements WishlistRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['customer_id' => 'customer_id', 'product_id' => 'product_id'];

    /**
     * @var list<string>
     */
    protected array $searchable = [];

    /**
     * @var list<string>
     */
    protected array $with = ['customer:id,name,email', 'product:id,name,slug'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(Wishlist $model)
    {
        parent::__construct($model);
    }
}
