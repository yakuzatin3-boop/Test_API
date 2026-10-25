<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\CategoryRequest;
use App\Http\Resources\Api\V1\Catalog\CategoryResource;
use App\Services\Catalog\CategoryService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class CategoryController extends CrudController
{
    public function __construct(private readonly CategoryService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return CategoryResource::class;
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(CategoryRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
