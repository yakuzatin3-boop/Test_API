<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository extends FilterableRepository implements CategoryRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['status' => 'status', 'parent_id' => 'parent_id'];

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

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
