<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\TagRequest;
use App\Http\Resources\Api\V1\Catalog\TagResource;
use App\Services\Catalog\TagService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class TagController extends CrudController
{
    public function __construct(private readonly TagService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return TagResource::class;
    }

    public function store(TagRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(TagRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
