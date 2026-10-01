# the-shop-mini-api

A small Laravel shop. Staff manage the catalog and customers from a hand-built admin panel. Customers are ordinary users and never see that panel.

The admin UI is plain Blade. It does not use Breeze or Filament.

## Admin panel

Sign in at `/admin/login`. Only users with `is_admin` can get past the login; everyone else receives a 403.

From the dashboard you can:

- Create, edit, and delete **products** (name, unique slug, description, price, stock)
- Create, edit, and delete **users**

After seeding, an admin account is available:

- Email: `admin@example.com`
- Password: `password`

The seeders also create 10 customers and 100 products.

## Stack

Laravel 12, PHP 8.4, and Laravel Sanctum. Sanctum is installed on the user model; the customer API (public product list, product by slug, and placing an order) is still to be built.
