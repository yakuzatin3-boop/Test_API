<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator;
}
