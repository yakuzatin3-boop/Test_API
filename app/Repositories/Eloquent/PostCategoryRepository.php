<?php

namespace App\Repositories\Eloquent;

use App\Models\PostCategory;
use App\Repositories\Contracts\PostCategoryRepositoryInterface;

class PostCategoryRepository extends FilterableRepository implements PostCategoryRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = [];

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
    protected array $withCount = ['posts'];

    public function __construct(PostCategory $model)
    {
        parent::__construct($model);
    }
}
