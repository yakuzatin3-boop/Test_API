<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\TagRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class TagService extends CrudService
{
    public function __construct(TagRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->loadCount(['posts']);
    }
}
