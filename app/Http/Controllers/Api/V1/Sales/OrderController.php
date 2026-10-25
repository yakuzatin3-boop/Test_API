<?php

namespace App\Http\Controllers\Api\V1\Sales;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Sales\OrderRequest;
use App\Http\Resources\Api\V1\Sales\OrderResource;
use App\Services\CrudService;
use App\Services\Sales\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends CrudController
{
    public function __construct(private readonly OrderService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return OrderResource::class;
    }

    public function store(OrderRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(OrderRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
