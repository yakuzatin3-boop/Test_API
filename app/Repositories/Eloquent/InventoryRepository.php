<?php

namespace App\Repositories\Eloquent;

use App\Models\Inventory;
use App\Repositories\Contracts\InventoryRepositoryInterface;

class InventoryRepository extends FilterableRepository implements InventoryRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['product_id' => 'product_id', 'variant_id' => 'variant_id'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['warehouse_location'];

    /**
     * @var list<string>
     */
    protected array $with = ['product:id,name,slug', 'variant:id,product_id,sku,size,color'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(Inventory $model)
    {
        parent::__construct($model);
    }
}
