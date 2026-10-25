<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository extends FilterableRepository implements PostRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['user_id' => 'user_id', 'category_id' => 'category_id', 'status' => 'status'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['title', 'slug'];

    /**
     * @var list<string>
     */
    protected array $with = ['user:id,name', 'category:id,name,slug'];

    /**
     * @var list<string>
     */
    protected array $withCount = ['tags'];

    public function __construct(Post $model)
    {
        parent::__construct($model);
    }
}
