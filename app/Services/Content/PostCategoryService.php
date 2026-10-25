<?php

namespace App\Services\Content;

use App\Repositories\Contracts\PostCategoryRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class PostCategoryService extends CrudService
{
    public function __construct(PostCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->loadCount(['posts']);
    }
}
