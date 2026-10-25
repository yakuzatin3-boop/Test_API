<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class ReviewService extends CrudService
{
    public function __construct(ReviewRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['product:id,name,slug', 'customer:id,name,email']);
    }
}
