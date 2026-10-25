<?php

namespace App\Http\Controllers\Api\V1\Customers;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Customers\AddressRequest;
use App\Http\Resources\Api\V1\Customers\AddressResource;
use App\Services\CrudService;
use App\Services\Customers\AddressService;
use Illuminate\Http\JsonResponse;

class AddressController extends CrudController
{
    public function __construct(private readonly AddressService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return AddressResource::class;
    }

    public function store(AddressRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(AddressRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
