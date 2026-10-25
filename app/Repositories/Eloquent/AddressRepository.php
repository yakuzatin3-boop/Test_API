<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;

class AddressRepository extends FilterableRepository implements AddressRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['customer_id' => 'customer_id', 'type' => 'type', 'is_default' => 'is_default'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['address_line1', 'city', 'country'];

    /**
     * @var list<string>
     */
    protected array $with = ['customer:id,name,email'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(Address $model)
    {
        parent::__construct($model);
    }
}
