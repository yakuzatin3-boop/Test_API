<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository extends FilterableRepository implements CustomerRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['user_id' => 'user_id'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['name', 'email', 'phone'];

    /**
     * @var list<string>
     */
    protected array $with = ['user:id,name,email,role,status'];

    /**
     * @var list<string>
     */
    protected array $withCount = ['addresses', 'orders'];

    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }
}
