<?php

namespace App\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

abstract class FilterableRepository extends BaseRepository
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = [];

    /**
     * @var list<string>
     */
    protected array $searchable = [];

    /**
     * @var list<string>
     */
    protected array $booleanFilters = [];

    /**
     * @var list<string>
     */
    protected array $with = [];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if ($this->with !== []) {
            $query->with($this->with);
        }

        if ($this->withCount !== []) {
            $query->withCount($this->withCount);
        }

        if (! empty($filters['search']) && $this->searchable !== []) {
            $search = (string) $filters['search'];

            $query->where(function (Builder $query) use ($search): void {
                foreach ($this->searchable as $column) {
                    $query->orWhereLike($column, "%{$search}%");
                }
            });
        }

        foreach ($this->filterable as $key => $column) {
            $value = $filters[$key] ?? null;

            if ($value === null || $value === '') {
                continue;
            }

            if (in_array($key, $this->booleanFilters, true)) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            $query->where($column, $value);
        }

        return $query->latest('id')->paginate($perPage)->withQueryString();
    }
}
