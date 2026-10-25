<?php

namespace App\Http\Controllers\Api\V1\Sales;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Sales\ShipmentRequest;
use App\Http\Resources\Api\V1\Sales\ShipmentResource;
use App\Services\CrudService;
use App\Services\Sales\ShipmentService;
use Illuminate\Http\JsonResponse;

class ShipmentController extends CrudController
{
    public function __construct(private readonly ShipmentService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return ShipmentResource::class;
    }

    public function store(ShipmentRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(ShipmentRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
