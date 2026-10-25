<?php

namespace Tests\Feature\Api\V1\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_crud_flow(): void
    {
        $created = $this->postJson('/api/v1/brands', [
            'name' => 'Acme',
            'slug' => 'acme',
            'status' => 'active',
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Acme')
            ->json('data');

        $this->getJson('/api/v1/brands?search=Acme')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->patchJson("/api/v1/brands/{$created['id']}", [
            'name' => 'Acme Updated',
        ])->assertOk()
            ->assertJsonPath('data.name', 'Acme Updated');

        $this->deleteJson("/api/v1/brands/{$created['id']}")
            ->assertNoContent();

        $this->assertDatabaseMissing('brands', ['id' => $created['id']]);
    }

    public function test_category_cannot_be_its_own_parent(): void
    {
        $category = Category::query()->create([
            'name' => 'Phones',
            'slug' => 'phones',
            'status' => 'active',
        ]);

        $this->patchJson("/api/v1/categories/{$category->id}", [
            'parent_id' => $category->id,
        ])->assertUnprocessable()
            ->assertJsonPath('message', 'A category cannot be its own parent.');
    }

    public function test_product_image_thumbnail_is_unique_per_product(): void
    {
        $product = Product::query()->create([
            'name' => 'Phone',
            'slug' => 'phone',
            'sku' => 'SKU-IMG-1',
            'price' => 100,
        ]);

        $first = $this->postJson('/api/v1/product-images', [
            'product_id' => $product->id,
            'image_path' => 'products/one.jpg',
            'is_thumbnail' => true,
        ])->assertCreated()->json('data');

        $second = $this->postJson('/api/v1/product-images', [
            'product_id' => $product->id,
            'image_path' => 'products/two.jpg',
            'is_thumbnail' => true,
        ])->assertCreated()->json('data');

        $this->assertDatabaseHas('product_images', ['id' => $first['id'], 'is_thumbnail' => false]);
        $this->assertDatabaseHas('product_images', ['id' => $second['id'], 'is_thumbnail' => true]);
    }
}
