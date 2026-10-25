<?php

namespace App\Services\Customers;

use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AddressService extends CrudService
{
    public function __construct(AddressRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['customer:id,name,email']);
    }

    public function create(array $attributes): Model
    {
        return DB::transaction(function () use ($attributes) {
            if (! empty($attributes['is_default'])) {
                Address::query()
                    ->where('customer_id', $attributes['customer_id'])
                    ->update(['is_default' => false]);
            }

            return parent::create($attributes);
        });
    }

    public function update(Model $model, array $attributes): Model
    {
        return DB::transaction(function () use ($model, $attributes) {
            if (! empty($attributes['is_default'])) {
                Address::query()
                    ->where('customer_id', $model->customer_id)
                    ->whereKeyNot($model->getKey())
                    ->update(['is_default' => false]);
            }

            return parent::update($model, $attributes);
        });
    }
}
