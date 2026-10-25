# E-commerce Post System API

Back-office and storefront API for an e-commerce platform, built with **Laravel 12**,
**PostgreSQL**, and a layered architecture designed for long-term maintainability.

## Stack

- PHP 8.2+
- Laravel 12
- PostgreSQL
- PHPUnit 11
- Laravel Pint

## Architecture

The project uses a layered architecture:

```text
Route → Middleware → FormRequest → Controller → Service → Repository → Model
                                                      ↓
                                                  Resource → JSON
```

- `app/Http` — controllers, requests, resources, middleware
- `app/Services` — business rules and orchestration
- `app/Repositories` — persistence, isolated behind interfaces
- `app/Enums` — canonical status/role values
- `app/Models` — Eloquent models and relationships
- `routes/api/v1.php` — versioned API routes

Read the full guide: **[docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)**.

## Requirements

- PHP 8.2 or newer with `pdo_pgsql`
- Composer
- PostgreSQL 14+
- Node.js 20+ (only for frontend assets)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure the database in `.env`:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ecommerce_postsystem
DB_USERNAME=postgres
DB_PASSWORD=
```

Then run the migrations:

```bash
php artisan migrate
php artisan serve
```

The API is available at `http://localhost:8000/api/v1`.

## Endpoints

Every resource below exposes the same five REST actions:

| Method | URI | Description |
| --- | --- | --- |
| `GET` | `/api/v1/{resource}` | Paginated, filterable list |
| `POST` | `/api/v1/{resource}` | Create |
| `GET` | `/api/v1/{resource}/{id}` | Show |
| `PATCH`/`PUT` | `/api/v1/{resource}/{id}` | Update |
| `DELETE` | `/api/v1/{resource}/{id}` | Delete |

### Catalog

| Resource | URI | List filters |
| --- | --- | --- |
| Products | `/api/v1/products` | `search`, `category_id`, `brand_id`, `status`, `is_active`, `is_featured`, `min_price`, `max_price`, `sort`, `per_page` |
| Brands | `/api/v1/brands` | `search`, `status` |
| Categories | `/api/v1/categories` | `search`, `parent_id`, `status` |
| Tags | `/api/v1/tags` | `search` |
| Product images | `/api/v1/product-images` | `product_id` |
| Product variants | `/api/v1/product-variants` | `search`, `product_id`, `status` |
| Inventory | `/api/v1/inventory` | `search`, `product_id`, `variant_id` |
| Reviews | `/api/v1/reviews` | `search`, `product_id`, `customer_id`, `status`, `rating` |
| Wishlists | `/api/v1/wishlists` | `customer_id`, `product_id` |

### Sales

| Resource | URI | List filters |
| --- | --- | --- |
| Carts | `/api/v1/carts` | `customer_id`, `status` |
| Cart items | `/api/v1/cart-items` | `cart_id`, `product_id`, `variant_id` |
| Orders | `/api/v1/orders` | `search`, `customer_id`, `status` |
| Order items | `/api/v1/order-items` | `search`, `order_id`, `product_id`, `variant_id` |
| Payments | `/api/v1/payments` | `search`, `order_id`, `status`, `payment_method` |
| Shipments | `/api/v1/shipments` | `search`, `order_id`, `status`, `carrier` |

Sales rules handled by services:

- Adding the same product/variant to a cart merges quantities instead of duplicating rows.
- Cart item prices fall back to the variant price, then the product price.
- Order subtotal/total are recalculated whenever order items or fees change.
- Orders receive a generated `order_number` and `placed_at` when omitted.
- Payments with status `paid` get `paid_at` automatically.
- Shipments fill `shipped_at`/`delivered_at` from their status and inherit the order address.

### Customers, content, and access

| Resource | URI | List filters |
| --- | --- | --- |
| Customers | `/api/v1/customers` | `search`, `user_id` |
| Addresses | `/api/v1/addresses` | `search`, `customer_id`, `type`, `is_default` |
| Posts | `/api/v1/posts` | `search`, `user_id`, `category_id`, `status` |
| Post categories | `/api/v1/post-categories` | `search` |
| Users | `/api/v1/users` | `search`, `role`, `status`, `is_active` |

Marking an address as default clears the previous default for that customer. Posts accept a
`tags` array of tag IDs and sync the pivot on create/update; publishing a post sets
`published_at` when it is empty. User passwords are hashed and never serialized.

Example:

```bash
curl "http://localhost:8000/api/v1/products?search=phone&status=active&sort=price_asc"
```

Responses use a consistent envelope:

```json
{
  "data": [],
  "links": {},
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 0,
    "last_page": 1
  }
}
```

Validation failures return `422` with `message` and `errors`; missing resources return `404`
with a `message`.

## Testing and code style

```bash
php artisan test
vendor/bin/pint --test
vendor/bin/pint
```

The feature tests run against in-memory SQLite. Local development targets PostgreSQL.

## Project status

Implemented:

- Versioned API routing (`/api/v1`)
- Unified JSON negotiation, error rendering, and API rate limiting
- Repository + service layers with container bindings
- Shared CRUD base classes (`CrudController`, `CrudService`, `FilterableRepository`)
- Form requests, API resources, and pagination conventions
- Status/role enums
- Catalog: products, brands, categories, tags, images, variants, inventory, reviews, wishlists
- Sales: carts, cart items, orders, order items, payments, shipments
- Customers, addresses, posts, post categories, and users
- Feature tests for product, catalog, sales, customer/address, post, and user flows

Planned: authentication (Sanctum), authorization policies, checkout orchestration from cart to
order, stock reservation, and generated OpenAPI documentation.
