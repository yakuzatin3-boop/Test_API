<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Http\Requests\Api\V1\IndexRequest;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

abstract class CrudController extends ApiController
{
    abstract protected function service(): CrudService;

    /**
     * @return class-string<JsonResource>
     */
    abstract protected function resourceClass(): string;

    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $resourceClass = $this->resourceClass();

        return $resourceClass::collection(
            $this->service()->paginate($request->validated(), $this->perPage($request))
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $model = $this->service()->detail($this->service()->find($id));
        $resourceClass = $this->resourceClass();

        return (new $resourceClass($model))->response();
    }

    public function destroy(int|string $id): Response
    {
        $this->service()->delete($this->service()->find($id));

        return response()->noContent();
    }

    protected function storeResource(ApiFormRequest $request): JsonResponse
    {
        $model = $this->service()->create($request->validated());
        $resourceClass = $this->resourceClass();

        return (new $resourceClass($this->service()->detail($model)))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    protected function updateResource(ApiFormRequest $request, int|string $id): JsonResponse
    {
        $model = $this->service()->update($this->service()->find($id), $request->validated());
        $resourceClass = $this->resourceClass();

        return (new $resourceClass($this->service()->detail($model)))->response();
    }
}
