<?php

namespace App\Services;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

abstract class CrudService
{
    public function __construct(
        protected readonly BaseRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateFiltered($filters, $perPage);
    }

    public function find(int|string $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function detail(Model $model): Model
    {
        return $model;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Model
    {
        return $this->repository->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $model, array $attributes): Model
    {
        return $this->repository->update($model->getKey(), $attributes);
    }

    public function delete(Model $model): bool
    {
        return $this->repository->delete($model->getKey());
    }
}
