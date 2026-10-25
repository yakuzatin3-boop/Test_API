<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Catalog\IndexProductRequest;
use App\Http\Requests\Api\V1\Catalog\StoreProductRequest;
use App\Http\Requests\Api\V1\Catalog\UpdateProductRequest;
use App\Http\Resources\Api\V1\Catalog\ProductResource;
use App\Models\Product;
use App\Services\Catalog\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends ApiController
{
    public function __construct(
        private readonly ProductService $products,
    ) {}

    public function index(IndexProductRequest $request): AnonymousResourceCollection
    {
        $products = $this->products->paginate(
            $request->validated(),
            $this->perPage($request),
        );

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->products->create($request->validated());

        return (new ProductResource($this->products->detail($product)))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($this->products->detail($product));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product = $this->products->update($product, $request->validated());

        return new ProductResource($this->products->detail($product));
    }

    public function destroy(Product $product): Response
    {
        $this->products->delete($product);

        return response()->noContent();
    }
}
