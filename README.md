# PaxiliArt

A Laravel marketplace for discovering, buying, and selling digital artwork. The interface follows the supplied PaxiliArt UI reference, including the cream-and-orange visual theme, the artwork market, account screens, checkout, seller studio, and admin dashboard.

## Run locally

Requirements: PHP 8.3+, Composer, Node.js and npm.

From the project folder in PowerShell:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
npm.cmd install
npm.cmd run build
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`. The default database is the included SQLite file at `database/database.sqlite`.

### Demo accounts

The seeder creates these local demo accounts. Each starts with the password `password`.

| Role | Email |
| --- | --- |
| Administrator | `admin@admin.pax` |
| Artist | `neith@paxiliart.test` |
| Artist | `kim@paxiliart.test` |
| Artist | `jay@paxiliart.test` |
| Artist | `brix@paxiliart.test` |
| Customer | `collector@paxiliart.test` |

Change or remove the seeded passwords before deploying anywhere public.

## Included features

- Search, category filters, sorting, featured pieces, artwork detail pages, and artist profiles.
- Account registration, login, logout, and role-based customer, artist, and administrator screens.
- Artist studio with artwork preview and private digital-file uploads; submitted or edited art goes into admin review.
- Admin dashboard, sales overview, order and user lists, and artwork approval/rejection.
- Persistent cart, demo checkout with GCash, Maya, card, and PayPal choices, order history/details, and purchase-checked downloads.

**Payments are in test mode:** the checkout records a completed demo order and never contacts or charges a payment provider. Live GCash, Maya, card, and PayPal processing is not configured; connect a provider and configure its credentials before accepting real payments. Password-reset messages use Laravel's log mailer by default; configure an email provider for delivered email. Digital files are stored privately; only an artwork preview is public.

## Tests

```powershell
php artisan test
```
