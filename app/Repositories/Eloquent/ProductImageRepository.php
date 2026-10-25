<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductImage;
use App\Repositories\Contracts\ProductImageRepositoryInterface;

class ProductImageRepository extends FilterableRepository implements ProductImageRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['product_id' => 'product_id'];

    /**
     * @var list<string>
     */
    protected array $searchable = [];

    /**
     * @var list<string>
     */
    protected array $with = ['product:id,name,slug'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(ProductImage $model)
    {
        parent::__construct($model);
    }
}
