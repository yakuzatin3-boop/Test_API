<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\InventoryRequest;
use App\Http\Resources\Api\V1\Catalog\InventoryResource;
use App\Services\Catalog\InventoryService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class InventoryController extends CrudController
{
    public function __construct(private readonly InventoryService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return InventoryResource::class;
    }

    public function store(InventoryRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(InventoryRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
