<?php

namespace App\Http\Controllers\Api\V1\Content;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Content\PostRequest;
use App\Http\Resources\Api\V1\Content\PostResource;
use App\Services\Content\PostService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class PostController extends CrudController
{
    public function __construct(private readonly PostService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return PostResource::class;
    }

    public function store(PostRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(PostRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
