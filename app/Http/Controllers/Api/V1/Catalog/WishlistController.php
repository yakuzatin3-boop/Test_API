<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Catalog\WishlistRequest;
use App\Http\Resources\Api\V1\Catalog\WishlistResource;
use App\Services\Catalog\WishlistService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class WishlistController extends CrudController
{
    public function __construct(private readonly WishlistService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return WishlistResource::class;
    }

    public function store(WishlistRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(WishlistRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
