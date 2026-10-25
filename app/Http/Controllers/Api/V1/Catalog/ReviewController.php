<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\ReviewRequest;
use App\Http\Resources\Api\V1\Catalog\ReviewResource;
use App\Services\Catalog\ReviewService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class ReviewController extends CrudController
{
    public function __construct(private readonly ReviewService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return ReviewResource::class;
    }

    public function store(ReviewRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(ReviewRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
