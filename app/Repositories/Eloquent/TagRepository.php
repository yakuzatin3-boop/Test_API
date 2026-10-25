<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;

class TagRepository extends FilterableRepository implements TagRepositoryInterface
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

    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }
}
