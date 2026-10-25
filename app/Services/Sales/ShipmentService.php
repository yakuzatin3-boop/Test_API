<?php

namespace App\Services\Sales;

use App\Enums\ShipmentStatus;
use App\Models\Order;
use App\Repositories\Contracts\ShipmentRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class ShipmentService extends CrudService
{
    public function __construct(ShipmentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['order:id,order_number,status']);
    }

    public function create(array $attributes): Model
    {
        $attributes = $this->applyTimestamps($attributes);

        if (empty($attributes['shipping_address']) && ! empty($attributes['order_id'])) {
            $attributes['shipping_address'] = Order::query()
                ->whereKey($attributes['order_id'])
                ->value('shipping_address');
        }

        return parent::create($attributes);
    }

    public function update(Model $model, array $attributes): Model
    {
        $attributes = array_merge([
            'status' => $model->status,
            'shipped_at' => $model->shipped_at,
            'delivered_at' => $model->delivered_at,
        ], $attributes);

        return parent::update($model, $this->applyTimestamps($attributes));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function applyTimestamps(array $attributes): array
    {
        $status = $attributes['status'] ?? null;

        if (in_array($status, [ShipmentStatus::Shipped->value, ShipmentStatus::InTransit->value], true) && empty($attributes['shipped_at'])) {
            $attributes['shipped_at'] = now();
        }

        if ($status === ShipmentStatus::Delivered->value && empty($attributes['delivered_at'])) {
            $attributes['delivered_at'] = now();
        }

        return $attributes;
    }
}
