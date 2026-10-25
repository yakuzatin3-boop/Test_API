<?php

namespace App\Http\Controllers\Api\V1\Customers;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Customers\CustomerRequest;
use App\Http\Resources\Api\V1\Customers\CustomerResource;
use App\Services\CrudService;
use App\Services\Customers\CustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends CrudController
{
    public function __construct(private readonly CustomerService $service) {}

    protected function service(): CrudService
    {
        return $this->service;
    }

    protected function resourceClass(): string
    {
        return CustomerResource::class;
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        return $this->storeResource($request);
    }

    public function update(CustomerRequest $request, int|string $id): JsonResponse
    {
        return $this->updateResource($request, $id);
    }
}
