<?php

namespace App\Services\Customers;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends CrudService
{
    public function __construct(CustomerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['user:id,name,email,role,status', 'addresses', 'orders:id,customer_id,order_number,status,total,placed_at'])->loadCount(['addresses', 'orders', 'reviews', 'wishlists']);
    }
}
