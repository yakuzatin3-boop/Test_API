<?php

namespace App\Http\Controllers\Api\V1\Content;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Content\PostCategoryRequest;
use App\Http\Resources\Api\V1\Content\PostCategoryResource;
use App\Services\Content\PostCategoryService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class PostCategoryController extends CrudController
{
    public function __construct(private readonly PostCategoryService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return PostCategoryResource::class;
    }

    public function store(PostCategoryRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(PostCategoryRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
