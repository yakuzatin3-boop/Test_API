<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentRepository extends FilterableRepository implements PaymentRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['order_id' => 'order_id', 'status' => 'status', 'payment_method' => 'payment_method'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['transaction_id'];

    /**
     * @var list<string>
     */
    protected array $with = ['order:id,order_number,total,status'];

    /**
     * @var list<string>
     */
    protected array $withCount = [];

    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
}
