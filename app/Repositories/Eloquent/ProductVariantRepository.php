<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductVariant;
use App\Repositories\Contracts\ProductVariantRepositoryInterface;

class ProductVariantRepository extends FilterableRepository implements ProductVariantRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['product_id' => 'product_id', 'status' => 'status'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['sku', 'size', 'color'];

    /**
     * @var list<string>
     */
    protected array $with = ['product:id,name,slug'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(ProductVariant $model)
    {
        parent::__construct($model);
    }
}
