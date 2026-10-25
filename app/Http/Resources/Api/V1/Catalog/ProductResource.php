<?php

namespace App\Http\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => (float) $this->price,
            'original_price' => $this->original_price === null ? null : (float) $this->original_price,
            'stock' => (int) $this->stock,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'is_bestseller' => (bool) $this->is_bestseller,
            'is_flash_sale' => (bool) $this->is_flash_sale,
            'is_active' => (bool) $this->is_active,
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null),
            'brand' => $this->whenLoaded('brand', fn () => $this->brand ? [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ] : null),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'reviews_count' => $this->whenCounted('reviews'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
