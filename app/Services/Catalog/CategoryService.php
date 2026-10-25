<?php

namespace App\Services\Catalog;

use App\Exceptions\BusinessException;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends CrudService
{
    public function __construct(CategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['parent:id,name,slug', 'children:id,name,parent_id,slug'])->loadCount(['products']);
    }

    public function update(Model $model, array $attributes): Model
    {
        if (isset($attributes['parent_id']) && (int) $attributes['parent_id'] === (int) $model->getKey()) {
            throw new BusinessException('A category cannot be its own parent.');
        }

        return parent::update($model, $attributes);
    }
}
