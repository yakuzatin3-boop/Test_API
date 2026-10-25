<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * @param  list<string>  $columns
     * @return Collection<int, Model>
     */
    public function all(array $columns = ['*']): Collection;

    public function find(int|string $id): ?Model;

    public function findOrFail(int|string $id): Model;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Model;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(int|string $id, array $attributes): Model;

    public function delete(int|string $id): bool;

    /**
     * @param  list<string>  $columns
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator;
}
