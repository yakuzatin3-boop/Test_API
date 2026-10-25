<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use App\Repositories\Contracts\BrandRepositoryInterface;

class BrandRepository extends FilterableRepository implements BrandRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['status' => 'status'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['name', 'slug'];

    /**
     * @var list<string>
     */
    protected array $with = [];

    /**
     * @var list<string>
     */
    protected array $withCount = ['products'];

    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}
