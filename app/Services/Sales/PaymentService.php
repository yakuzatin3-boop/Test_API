<?php

namespace App\Services\Sales;

use App\Enums\PaymentStatus;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class PaymentService extends CrudService
{
    public function __construct(PaymentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['order:id,order_number,total,status']);
    }

    public function create(array $attributes): Model
    {
        return parent::create($this->applyPaidAt($attributes));
    }

    public function update(Model $model, array $attributes): Model
    {
        $attributes = array_merge([
            'status' => $model->status,
            'paid_at' => $model->paid_at,
        ], $attributes);

        return parent::update($model, $this->applyPaidAt($attributes));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function applyPaidAt(array $attributes): array
    {
        if (($attributes['status'] ?? null) === PaymentStatus::Paid->value && empty($attributes['paid_at'])) {
            $attributes['paid_at'] = now();
        }

        return $attributes;
    }
}
