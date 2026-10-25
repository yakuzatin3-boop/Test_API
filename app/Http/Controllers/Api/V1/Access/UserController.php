<?php

namespace App\Http\Controllers\Api\V1\Access;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Access\UserRequest;
use App\Http\Resources\Api\V1\Access\UserResource;
use App\Services\Access\UserService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;

class UserController extends CrudController
{
    public function __construct(private readonly UserService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return UserResource::class;
    }

    public function store(UserRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(UserRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
