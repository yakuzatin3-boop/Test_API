<?php

namespace App\Http\Controllers\Api\V1\Sales;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Sales\CartRequest;
use App\Http\Resources\Api\V1\Sales\CartResource;
use App\Services\CrudService;
use App\Services\Sales\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends CrudController
{
    public function __construct(private readonly CartService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return CartResource::class;
    }

    public function store(CartRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(CartRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
