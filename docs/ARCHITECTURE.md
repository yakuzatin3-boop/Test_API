# Architecture

This project is an e-commerce back-office API built on **Laravel 12** and **PostgreSQL**. The
codebase follows a layered, Laravel-native architecture: HTTP concerns stay in the HTTP layer,
business rules live in services, and database access is isolated behind repositories.

The `Product` module is the reference implementation. Every new module should follow the same
shape.

## Goals

- **Thin controllers** — no business logic, no queries, no validation.
- **Explicit boundaries** — HTTP, business logic, and persistence are separate and testable.
- **Versioned API** — routes and resources are namespaced by version (`v1`).
- **Domain grouping** — code is grouped by business area (`Catalog`, `Sales`, `Content`, ...).
- **Predictable contracts** — every endpoint returns the same JSON shapes and status codes.

## Directory structure

```text
app/
├── Enums/                              # Backed enums shared by validation, services, UI
│   ├── Concerns/HasOptions.php
│   ├── ActiveStatus.php
│   ├── CartStatus.php
│   ├── OrderStatus.php
│   ├── PaymentStatus.php
│   ├── PostStatus.php
│   ├── ProductStatus.php
│   ├── ReviewStatus.php
│   ├── ShipmentStatus.php
│   └── UserRole.php
├── Exceptions/
│   └── BusinessException.php           # Expected domain failures (422/409/etc.)
├── Http/
│   ├── Controllers/
│   │   └── Api/V1/
│   │       ├── ApiController.php       # Shared API controller helpers
│   │       ├── CrudController.php      # Generic resource controller
│   │       ├── Access/                 # UserController
│   │       ├── Catalog/                # Product, Brand, Category, Tag, ProductImage,
│   │       │                           # ProductVariant, Inventory, Review, Wishlist
│   │       ├── Content/                # Post, PostCategory
│   │       ├── Customers/              # Customer, Address
│   │       └── Sales/                  # Cart, CartItem, Order, OrderItem, Payment, Shipment
│   ├── Middleware/
│   │   └── ForceJsonResponse.php       # API always negotiates JSON
│   ├── Requests/
│   │   └── Api/V1/
│   │       ├── ApiFormRequest.php      # Store/update rule helpers
│   │       ├── IndexRequest.php        # Shared list query validation
│   │       └── {Access,Catalog,Content,Customers,Sales}/
│   │           └── *Request.php
│   └── Resources/
│       └── Api/V1/
│           └── {Access,Catalog,Content,Customers,Sales}/
│               └── *Resource.php
├── Models/                             # Eloquent models: relationships, casts, scopes
├── Providers/
│   ├── AppServiceProvider.php          # Rate limiting, strict Eloquent mode
│   └── RepositoryServiceProvider.php   # Interface → implementation bindings
├── Repositories/
│   ├── Contracts/
│   │   ├── BaseRepositoryInterface.php
│   │   └── *RepositoryInterface.php
│   └── Eloquent/
│       ├── BaseRepository.php
│       ├── FilterableRepository.php    # Shared filtering/search/pagination
│       └── *Repository.php
└── Services/
    ├── CrudService.php                 # Generic CRUD orchestration
    └── {Access,Catalog,Content,Customers,Sales}/
        └── *Service.php

bootstrap/
├── app.php                             # Routing, middleware, exception rendering
└── providers.php                       # Registered service providers

routes/
├── api.php                             # Version prefix (/api/v1)
└── api/v1.php                          # v1 endpoints

tests/
└── Feature/Api/V1/{Account,Catalog,Sales}/*Test.php
```

> The existing models live in `app/Models`. Keep them there until a module grows large enough to
> justify a dedicated domain namespace. Moving all models is a breaking change with no immediate
> benefit.

## Layer responsibilities

| Layer | Responsibility | Must **not** |
| --- | --- | --- |
| Route | Map URL + HTTP verb to a controller action | Contain logic |
| FormRequest | Authorize the action and validate input | Query the database for business decisions |
| Controller | Translate HTTP → service call → resource | Contain queries, transactions, or business rules |
| Service | Business rules, orchestration, transactions | Know about HTTP or responses |
| Repository | Eloquent queries and persistence | Contain business rules |
| Model | Relationships, casts, small scopes, accessors | Contain multi-step business workflows |
| Resource | Shape the public JSON contract | Leak internal columns or lazily load data |
| Enum | Canonical allowed values + labels | Depend on the database |

## Request lifecycle

```text
Request
  → ForceJsonResponse middleware
  → throttle:api
  → Route model binding (Product) or `{id}` parameter (generic CRUD)
  → FormRequest validation
  → Controller
      → Service
          → Repository
              → Eloquent model
      ← Service
  ← Resource (JSON)
```

## API conventions

- **Base path:** `/api/v1`
- **Content type:** JSON only (enforced by `ForceJsonResponse`)
- **Resource naming:** plural, kebab-case (`/products`, `/order-items`)
- **Status codes:** `200` read/update, `201` create, `204` delete, `422` validation/business rule,
  `404` missing resource, `429` rate limited, `500` unexpected error
- **Pagination:** `?page=2&per_page=25` (`per_page` max `100`)
- **Filtering/sorting:** explicit query parameters validated by the `Index*Request`

### Success — single resource

```json
{
  "data": {
    "id": 1,
    "name": "Test Product",
    "slug": "test-product",
    "sku": "SKU-100",
    "price": 99.99,
    "status": "active"
  }
}
```

### Success — paginated collection

```json
{
  "data": [],
  "links": {
    "first": "http://localhost/api/v1/products?page=1",
    "last": "http://localhost/api/v1/products?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 0,
    "last_page": 1
  }
}
```

### Validation error — `422`

```json
{
  "message": "The sku field is required.",
  "errors": {
    "sku": ["The sku field is required."]
  }
}
```

### Missing resource — `404`

```json
{
  "message": "Product not found."
}
```

### Business rule failure — `422`/`409`

Throw `App\Exceptions\BusinessException` from a service. The global handler renders it
consistently:

```php
throw new BusinessException(
    'This order can no longer be cancelled.',
    statusCode: 409,
);
```

## Shared CRUD layer

Most resources are plain CRUD, so they reuse five base classes instead of duplicating the same
controller, service, and repository code:

| Class | Role |
| --- | --- |
| `ApiFormRequest` | Authorizes requests and exposes `required()`, `nullable()`, `unique()` helpers that adapt to POST vs. PATCH/PUT |
| `IndexRequest` | Validates the shared list query (`search`, `status`, `per_page`, foreign keys, booleans, price range) |
| `FilterableRepository` | Turns `$filterable`, `$searchable`, `$with`, and `$withCount` maps into a paginated query |
| `CrudService` | Implements `paginate`, `find`, `detail`, `create`, `update`, and `delete` against a repository interface |
| `CrudController` | Implements `index`, `show`, `store`, `update`, and `destroy` using a service and a resource class |

A module overrides only what differs: filter/search maps, eager loads for list and detail, request
rules, resource fields, and service business rules. `Product` remains the fully custom reference
module; the rest extend the generic layer.

Business logic that goes beyond CRUD lives in services and is covered by feature tests:

- `Catalog\CategoryService` rejects a category being its own parent.
- `Catalog\ProductImageService` keeps one thumbnail per product.
- `Sales\CartItemService` merges duplicate cart lines and resolves prices.
- `Sales\OrderService` generates order numbers and recalculates totals.
- `Sales\OrderItemService` snapshots product name/SKU and recalculates its order.
- `Sales\PaymentService` stamps `paid_at` for paid payments.
- `Sales\ShipmentService` stamps shipment timestamps and copies the order address.
- `Customers\AddressService` enforces a single default address per customer.
- `Content\PostService` syncs tags and sets `published_at` on publish.
- `Access\UserService` ignores blank passwords on update.

## Resource groups

| Group | Namespace | Resources |
| --- | --- | --- |
| Catalog | `Api\V1\Catalog` | `products`, `brands`, `categories`, `tags`, `product-images`, `product-variants`, `inventory`, `reviews`, `wishlists` |
| Sales | `Api\V1\Sales` | `carts`, `cart-items`, `orders`, `order-items`, `payments`, `shipments` |
| Customers | `Api\V1\Customers` | `customers`, `addresses` |
| Content | `Api\V1\Content` | `posts`, `post-categories` |
| Access | `Api\V1\Access` | `users` |

Each resource exposes `GET /`, `POST /`, `GET /{id}`, `PATCH|PUT /{id}`, and `DELETE /{id}`.
`Product` additionally supports `sort` (`price_asc`, `price_desc`, `name_asc`, `name_desc`,
`newest`). See the README for the full filter matrix.

## Conventions

1. **One controller per resource**, one service per aggregate (`ProductService`).
2. **Suffixes are meaningful:** `Store*Request`, `Update*Request`, `Index*Request`,
   `*Resource`, `*Service`, `*Repository`, `*RepositoryInterface`.
3. **Depend on interfaces in services**, bind them in `RepositoryServiceProvider`.
4. **Wrap multi-write operations in `DB::transaction()`** inside the service.
5. **Eager load relationships** used by a resource. Eloquent strict mode is enabled outside
   production, so lazy loading fails fast instead of hiding N+1 queries.
6. **Never return models directly.** Always go through a resource.
7. **Keep enums as the single source of truth** for status values; validate with
   `Rule::enum(ProductStatus::class)`.
8. **Add a new API version, don't mutate v1.** Create `routes/api/v2.php` and
   `App\Http\Controllers\Api\V2`.
9. **Tests mirror the API namespace** (`tests/Feature/Api/V1/Catalog/ProductApiTest.php`).

## Adding a new module

Example: adding a supplier module.

1. Create `app/Repositories/Contracts/SupplierRepositoryInterface.php` extending
   `BaseRepositoryInterface`.
2. Create `app/Repositories/Eloquent/SupplierRepository.php` extending `FilterableRepository` and
   declare `$filterable`, `$searchable`, `$with`, and `$withCount`.
3. Bind the interface in `RepositoryServiceProvider`.
4. Create `app/Services/Supplier/SupplierService.php` extending `CrudService`; override `detail()`
   for eager loads and add any business methods.
5. Create `app/Http/Requests/Api/V1/Supplier/SupplierRequest.php` extending `ApiFormRequest`. Add a
   dedicated index request only when `IndexRequest` does not cover the filters.
6. Create `app/Http/Resources/Api/V1/Supplier/SupplierResource.php`.
7. Create `app/Http/Controllers/Api/V1/Supplier/SupplierController.php` extending `CrudController`.
8. Register `Route::apiResource('suppliers', SupplierController::class)` in `routes/api/v1.php`.
9. Add `tests/Feature/Api/V1/Supplier/SupplierTest.php` covering CRUD and any custom rules.

## Testing

```bash
php artisan test
php artisan test --filter=ProductApiTest
vendor/bin/pint --test
```

Tests run against in-memory SQLite (`phpunit.xml`), while local development uses PostgreSQL.
Keep migrations portable: there is a driver guard around the PostgreSQL-only `CHECK` constraint
in the reviews migration for this reason.

## Recommended next steps

- Add **Laravel Sanctum** (or Passport) for token authentication and protect write endpoints.
- Add **policies** for per-resource authorization; enable them in the FormRequest `authorize()`
  methods.
- Introduce **DTOs** (`app/DTOs`) only for complex payloads that outgrow validated arrays.
- Dispatch **domain events** (`OrderPlaced`, `PaymentCaptured`) and handle side effects with
  listeners/jobs.
- Add **OpenAPI/Scribe** documentation generated from routes and resources.
- Add a CI pipeline running `pint --test`, `php artisan test`, and a PostgreSQL migration check.
