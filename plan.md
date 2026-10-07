# the-shop-mini-api — Plan

Simple Laravel shop API + hand-built Admin Panel.  
**No Breeze. No Filament.**

## Models

| Model | Role |
|---|---|
| **Admin** | Access to Admin Panel (`User` with `is_admin = true`) |
| **User** | Client buying products — no Admin Panel access |
| **Product** | Shop catalog |
| **Order** | Purchase record (`user_id`, `product_id`) |

---

## Admin Panel (hand-built Blade)

Custom Blade admin UI with navbar: **User** and **Product**.  
Admin-only middleware (`is_admin`).

### Products (admin)

- `ProductController.php` — full CRUD including **Delete**
- `ProductStoreRequest.php`
- `ProductUpdateRequest.php`
- `ProductFactory`
- `ProductSeeder` — **100** products
- All needed forms for CRUD

### Users (admin)

- `UserController.php` — full CRUD including **Delete**
- `UserStoreRequest.php`
- `UserUpdateRequest.php`
- `UserFactory`
- `UserSeeder` — **10** users
- All needed forms for CRUD

---

## API Auth (Sanctum — customers)

### Register + Login
- `App\Http\Controllers\Api\AuthController`
  - `POST /api/register` — create user, return Sanctum token
  - `POST /api/login` — validate credentials, return Sanctum token
  - `POST /api/logout` — revoke current token (`auth:sanctum`)
- `App\Http\Requests\Api\RegisterRequest`
- `App\Http\Requests\Api\LoginRequest`
- Customers only (`is_admin = false` on register)

### Welcome email (Observer + Redis queue)
| Piece | Path / role |
|---|---|
| Observer | `app/Observers/UserObserver.php` on `User` `created` |
| Mailable | `app/Mail/WelcomeMail.php` |
| Queue | Redis (`QUEUE_CONNECTION=redis`) |

Flow:
1. User registers → `User` created
2. `UserObserver::created` runs
3. Observer queues `WelcomeMail` (Redis)
4. `php artisan queue:work redis` sends the email

Note: observer also runs if Admin creates a user in the panel.

## API (Sanctum authentication)

### Product (public — outside auth)

- `ProductController.php` (API)
- **Show** — one product by **slug**, via `ProductResource.php`
- **Index** — product list, with **Laravel Cache**

### Order (inside auth)

- `OrderController.php`
- **OrderProduct** — authenticated user buys a product

Customer API auth (login/register/token) built with Sanctum as needed — not Breeze.

---

## Event + Listener

| Piece | Path / role |
|---|---|
| Event | `app/Events/OrderPlaced.php` |
| Listener | `app/Listeners/SendOrderConfirmation.php` |

Flow:

1. Order is placed → dispatch `OrderPlaced`
2. `SendOrderConfirmation` hears it (Laravel auto-discovers listeners)
3. Listener **only queues** the confirmation email work (Laravel queues)

The listener reacts to a **custom event** you dispatch.

---

## Eloquent Observer (cache)

| Piece | Path / role |
|---|---|
| Observer | `app/Observers/ProductObserver.php` |
| Registration | on `Product` via `#[ObservedBy]` |

On **saved** / **deleted** → update the **cached product list**.

The observer reacts **automatically** when a Product is saved or deleted.

### Event vs Observer

| | Listener | Observer |
|---|---|---|
| Trigger | Custom event you dispatch (`OrderPlaced`) | Model lifecycle (`saved` / `deleted`) |
| Job here | Queue confirmation email | Refresh product list cache |

---

## Build order

1. Admin auth + middleware (`is_admin`) + layout/navbar
2. Product admin CRUD + forms + factory + seeder (100)
3. User admin CRUD + forms + factory + seeder (10)
4. API: public Product index (cached) + show by slug + `ProductResource`
5. `ProductObserver` — bust/refresh cache on save/delete
6. API: authenticated `OrderController` / OrderProduct
7. `OrderPlaced` + `SendOrderConfirmation` (queued mail)
8. Wire queues + mail for local testing

---

## Already in place

- Laravel 12
- Sanctum (`HasApiTokens` on User, `/api/user`)
- User model with `is_admin`
- Laravel Boost
- MySQL in Docker (`the-shop-mini-api`, port `3308`)
- `php artisan serve` → http://127.0.0.1:8000

**Not used:** Breeze, Filament
