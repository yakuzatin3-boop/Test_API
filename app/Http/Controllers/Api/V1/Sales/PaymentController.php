<?php

namespace App\Http\Controllers\Api\V1\Sales;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Sales\PaymentRequest;
use App\Http\Resources\Api\V1\Sales\PaymentResource;
use App\Services\CrudService;
use App\Services\Sales\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends CrudController
{
    public function __construct(private readonly PaymentService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return PaymentResource::class;
    }

    public function store(PaymentRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(PaymentRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
