<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\WishlistRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class WishlistService extends CrudService
{
    public function __construct(WishlistRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['customer:id,name,email', 'product:id,name,slug']);
    }
}
