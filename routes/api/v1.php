<?php

use App\Http\Controllers\Api\V1\Access\UserController;
use App\Http\Controllers\Api\V1\Catalog\BrandController;
use App\Http\Controllers\Api\V1\Catalog\CategoryController;
use App\Http\Controllers\Api\V1\Catalog\InventoryController;
use App\Http\Controllers\Api\V1\Catalog\ProductController;
use App\Http\Controllers\Api\V1\Catalog\ProductImageController;
use App\Http\Controllers\Api\V1\Catalog\ProductVariantController;
use App\Http\Controllers\Api\V1\Catalog\ReviewController;
use App\Http\Controllers\Api\V1\Catalog\TagController;
use App\Http\Controllers\Api\V1\Catalog\WishlistController;
use App\Http\Controllers\Api\V1\Content\PostCategoryController;
use App\Http\Controllers\Api\V1\Content\PostController;
use App\Http\Controllers\Api\V1\Customers\AddressController;
use App\Http\Controllers\Api\V1\Customers\CustomerController;
use App\Http\Controllers\Api\V1\Sales\CartController;
use App\Http\Controllers\Api\V1\Sales\CartItemController;
use App\Http\Controllers\Api\V1\Sales\OrderController;
use App\Http\Controllers\Api\V1\Sales\OrderItemController;
use App\Http\Controllers\Api\V1\Sales\PaymentController;
use App\Http\Controllers\Api\V1\Sales\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
*/

Route::apiResource('products', ProductController::class);

Route::apiResource('brands', BrandController::class)->parameters(['brands' => 'id']);
Route::apiResource('categories', CategoryController::class)->parameters(['categories' => 'id']);
Route::apiResource('tags', TagController::class)->parameters(['tags' => 'id']);

Route::apiResource('product-images', ProductImageController::class)->parameters(['product-images' => 'id']);
Route::apiResource('product-variants', ProductVariantController::class)->parameters(['product-variants' => 'id']);
Route::apiResource('inventory', InventoryController::class)->parameters(['inventory' => 'id']);
Route::apiResource('reviews', ReviewController::class)->parameters(['reviews' => 'id']);
Route::apiResource('wishlists', WishlistController::class)->parameters(['wishlists' => 'id']);

Route::apiResource('carts', CartController::class)->parameters(['carts' => 'id']);
Route::apiResource('cart-items', CartItemController::class)->parameters(['cart-items' => 'id']);
Route::apiResource('orders', OrderController::class)->parameters(['orders' => 'id']);
Route::apiResource('order-items', OrderItemController::class)->parameters(['order-items' => 'id']);
Route::apiResource('payments', PaymentController::class)->parameters(['payments' => 'id']);
Route::apiResource('shipments', ShipmentController::class)->parameters(['shipments' => 'id']);

Route::apiResource('customers', CustomerController::class)->parameters(['customers' => 'id']);
Route::apiResource('addresses', AddressController::class)->parameters(['addresses' => 'id']);

Route::apiResource('posts', PostController::class)->parameters(['posts' => 'id']);
Route::apiResource('post-categories', PostCategoryController::class)->parameters(['post-categories' => 'id']);

Route::apiResource('users', UserController::class)->parameters(['users' => 'id']);
