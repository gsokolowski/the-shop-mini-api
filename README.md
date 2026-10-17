# the-shop-mini-api

A small Laravel shop: hand-built Blade admin for staff, Sanctum token API for customers.

**Not used:** Breeze, Filament, npm/Vite.

## Stack

- Laravel 12 / PHP 8.4
- Laravel Sanctum (API tokens)
- MySQL in Docker (`the-shop-mini-api`, port `3308`)
- Redis queue (`QUEUE_CONNECTION=redis`)
- Mail logged locally (`MAIL_MAILER=log`) unless you configure SMTP

## Admin panel

Sign in at `/admin/login`. Only `is_admin` users get in; others get 403.

- Dashboard (user/product counts)
- Products CRUD (name, unique slug, description, price, stock)
- Users CRUD (admin accounts cannot be updated or deleted)

After seeding:

- Email: `admin@example.com`
- Password: `password`

Also seeds 10 customers and 100 products.

## Customer API

### Public

| Method | Path | Notes |
|---|---|---|
| `GET` | `/api/products` | Product list (Laravel Cache) |
| `GET` | `/api/products/{slug}` | Single product via `ProductResource` |

### Auth (Sanctum)

| Method | Path | Notes |
|---|---|---|
| `POST` | `/api/register` | Create customer (`is_admin = false`), return token |
| `POST` | `/api/login` | Return token |
| `POST` | `/api/logout` | Revoke current token (auth required) |
| `GET` | `/api/user` | Current user (auth required) |

### Orders (auth required)

| Method | Path | Notes |
|---|---|---|
| `GET` | `/api/orders` | Current user’s orders |
| `POST` | `/api/orders` | Body: `{ "product_ids": [1, 2] }` — decrements stock with row locks |

## Async behavior

- **User created** → `UserObserver` queues `WelcomeMail`
- **Product saved/deleted** → `ProductObserver` refreshes/busts product index cache
- **Order placed** → `OrderPlaced` → `SendOrderConfirmation` queues `OrderConfirmationMail`

Run a worker locally:

```bash
php artisan queue:work redis
```

## Local run

```bash
php artisan serve
# separate terminal:
php artisan queue:work redis
```

App: `http://127.0.0.1:8000`  
Admin: `http://127.0.0.1:8000/admin/login`
