<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class BrandService extends CrudService
{
    public function __construct(BrandRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->loadCount(['products']);
    }
}
