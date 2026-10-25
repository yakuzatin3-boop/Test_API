<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\ProductVariantRequest;
use App\Http\Resources\Api\V1\Catalog\ProductVariantResource;
use App\Services\Catalog\ProductVariantService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class ProductVariantController extends CrudController
{
    public function __construct(private readonly ProductVariantService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return ProductVariantResource::class;
    }

    public function store(ProductVariantRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(ProductVariantRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
