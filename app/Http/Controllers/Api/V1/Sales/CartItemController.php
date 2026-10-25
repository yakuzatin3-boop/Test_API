<?php

namespace App\Http\Controllers\Api\V1\Sales;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Sales\CartItemRequest;
use App\Http\Resources\Api\V1\Sales\CartItemResource;
use App\Services\CrudService;
use App\Services\Sales\CartItemService;
use Illuminate\Http\JsonResponse;

class CartItemController extends CrudController
{
    public function __construct(private readonly CartItemService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return CartItemResource::class;
    }

    public function store(CartItemRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(CartItemRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
