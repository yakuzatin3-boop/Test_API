<?php

namespace App\Repositories\Eloquent;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;

class ReviewRepository extends FilterableRepository implements ReviewRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['product_id' => 'product_id', 'customer_id' => 'customer_id', 'status' => 'status', 'rating' => 'rating'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['comment'];

    /**
     * @var list<string>
     */
    protected array $with = ['product:id,name,slug', 'customer:id,name,email'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(Review $model)
    {
        parent::__construct($model);
    }
}
