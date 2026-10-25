<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected readonly Model $model) {}

    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param  list<string>  $columns
     * @return Collection<int, Model>
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function find(int|string $id): ?Model
    {
        return $this->query()->find($id);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Model
    {
        return $this->query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(int|string $id, array $attributes): Model
    {
        $model = $this->findOrFail($id);

        $model->update($attributes);

        return $model->refresh();
    }

    public function delete(int|string $id): bool
    {
        return (bool) $this->findOrFail($id)->delete();
    }

    /**
     * @param  list<string>  $columns
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->paginate($perPage);
    }
}
