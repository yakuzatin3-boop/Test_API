<?php

namespace App\Services\Sales;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService extends CrudService
{
    public function __construct(OrderRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['customer:id,name,email', 'items.product:id,name,slug', 'payments:id,order_id,payment_method,amount,status,paid_at', 'shipments:id,order_id,tracking_number,carrier,status'])->loadCount(['items', 'payments', 'shipments']);
    }

    public function create(array $attributes): Model
    {
        return DB::transaction(function () use ($attributes) {
            $attributes['order_number'] ??= $this->generateOrderNumber();
            $attributes['placed_at'] ??= now();

            /** @var Order $order */
            $order = parent::create($attributes);

            return $this->recalculate($order);
        });
    }

    public function update(Model $model, array $attributes): Model
    {
        return DB::transaction(function () use ($model, $attributes) {
            /** @var Order $order */
            $order = parent::update($model, $attributes);

            return $this->recalculate($order);
        });
    }

    public function recalculate(Order $order): Order
    {
        $subtotal = (float) $order->items()->sum('subtotal');

        $order->subtotal = $subtotal;
        $order->total = max(0, $subtotal - (float) $order->discount + (float) $order->shipping_fee);
        $order->save();

        return $order;
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}
