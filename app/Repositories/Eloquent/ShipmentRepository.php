<?php

namespace App\Repositories\Eloquent;

use App\Models\Shipment;
use App\Repositories\Contracts\ShipmentRepositoryInterface;

class ShipmentRepository extends FilterableRepository implements ShipmentRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['order_id' => 'order_id', 'status' => 'status', 'carrier' => 'carrier'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['tracking_number', 'carrier'];

    /**
     * @var list<string>
     */
    protected array $with = ['order:id,order_number,status'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(Shipment $model)
    {
        parent::__construct($model);
    }
}
