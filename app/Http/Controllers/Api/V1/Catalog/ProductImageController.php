<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\ProductImageRequest;
use App\Http\Resources\Api\V1\Catalog\ProductImageResource;
use App\Services\Catalog\ProductImageService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class ProductImageController extends CrudController
{
    public function __construct(private readonly ProductImageService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return ProductImageResource::class;
    }

    public function store(ProductImageRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(ProductImageRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
