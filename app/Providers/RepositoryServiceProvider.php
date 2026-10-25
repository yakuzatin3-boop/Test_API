<?php

namespace App\Providers;

use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Repositories\Contracts\CartItemRepositoryInterface;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\PostCategoryRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ProductImageRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductVariantRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\ShipmentRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WishlistRepositoryInterface;
use App\Repositories\Eloquent\AddressRepository;
use App\Repositories\Eloquent\BrandRepository;
use App\Repositories\Eloquent\CartItemRepository;
use App\Repositories\Eloquent\CartRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\InventoryRepository;
use App\Repositories\Eloquent\OrderItemRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\PostCategoryRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\ProductImageRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\ProductVariantRepository;
use App\Repositories\Eloquent\ReviewRepository;
use App\Repositories\Eloquent\ShipmentRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WishlistRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    private const REPOSITORIES = [
        ProductRepositoryInterface::class => ProductRepository::class,
        BrandRepositoryInterface::class => BrandRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
        ProductImageRepositoryInterface::class => ProductImageRepository::class,
        ProductVariantRepositoryInterface::class => ProductVariantRepository::class,
        InventoryRepositoryInterface::class => InventoryRepository::class,
        ReviewRepositoryInterface::class => ReviewRepository::class,
        WishlistRepositoryInterface::class => WishlistRepository::class,
        CartRepositoryInterface::class => CartRepository::class,
        CartItemRepositoryInterface::class => CartItemRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class,
        OrderItemRepositoryInterface::class => OrderItemRepository::class,
        PaymentRepositoryInterface::class => PaymentRepository::class,
        ShipmentRepositoryInterface::class => ShipmentRepository::class,
        CustomerRepositoryInterface::class => CustomerRepository::class,
        AddressRepositoryInterface::class => AddressRepository::class,
        PostRepositoryInterface::class => PostRepository::class,
        PostCategoryRepositoryInterface::class => PostCategoryRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
    ];

    /**
     * Register repository bindings.
     */
    public function register(): void
    {
        foreach (self::REPOSITORIES as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
