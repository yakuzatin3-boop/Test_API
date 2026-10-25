<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\BrandRequest;
use App\Http\Resources\Api\V1\Catalog\BrandResource;
use App\Services\Catalog\BrandService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class BrandController extends CrudController
{
    public function __construct(private readonly BrandService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return BrandResource::class;
    }

    public function store(BrandRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(BrandRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
