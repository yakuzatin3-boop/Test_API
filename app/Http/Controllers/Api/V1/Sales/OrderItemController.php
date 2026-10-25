<?php

namespace App\Http\Controllers\Api\V1\Sales;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Sales\OrderItemRequest;
use App\Http\Resources\Api\V1\Sales\OrderItemResource;
use App\Services\CrudService;
use App\Services\Sales\OrderItemService;
use Illuminate\Http\JsonResponse;

class OrderItemController extends CrudController
{
    public function __construct(private readonly OrderItemService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return OrderItemResource::class;
    }

    public function store(OrderItemRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(OrderItemRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
