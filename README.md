# FoodHub Panel

Backend panel for managing restaurant/food business licensing, subscriptions, and client accounts. B2B SaaS platform built with Symfony 7.2.

## Features

- Client registration and onboarding (2-step flow with email confirmation)
- License and subscription management with trial periods and promo codes
- Add-ons and additional device provisioning (cameras, terminals, printers, etc.)
- Order processing with PayU payment gateway integration
- Invoice generation (PDF via wkhtmltopdf)
- Multi-tenant architecture with subdomain-based client isolation
- Multi-language support (PL, EN)
- OpenAPI documentation

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.3, Symfony 7.2 |
| Database | PostgreSQL 9.6, Doctrine ORM |
| Messaging | Symfony Messenger (CQRS) |
| Frontend | Twig, Bootstrap 4, jQuery, Webpack Encore |
| Payments | PayU (OpenPayU v2.3) |
| PDF | wkhtmltopdf |
| Infrastructure | Docker, Kubernetes/Helm, GitLab CI |

## Requirements

- PHP 8.3 with extensions: `bcmath`, `ctype`, `iconv`, `json`, `openssl`
- PostgreSQL 9.6+
- Node.js 10+
- Composer
- wkhtmltopdf (for invoice PDF generation)

## Local Setup

### With Docker

```bash
docker-compose up -d
```

App available at `http://localhost:8002`. PostgreSQL exposed on port `5430`.

### Manual

```bash
composer install
cp .env .env.local   # fill in required values
npm install
npm run build
php bin/console doctrine:migrations:migrate
```

### Environment Variables

Key variables to configure in `.env.local`:

```dotenv
DATABASE_URL=postgresql://fhp:fhp@localhost:5430/fhp

API_PROTOCOL=https
API_DOMAIN=your-api-domain.com

MAILER_URL=smtp://...

PAYU_POS_ID=
PAYU_MD5=
PAYU_CLIENT_ID=
PAYU_CLIENT_SECRET=

FHP_DOMAIN_RESTRICTION=panel.yourdomain.com
REGISTER_DOMAIN_RESTRICTION=register.yourdomain.com

TRIAL_DAYS=14
TRIAL_DAYS_WITH_CODE=30

WKHTMLTOPDF_PATH=/usr/bin/wkhtmltopdf
WKHTMLTOIMAGE_PATH=/usr/bin/wkhtmltoimage
```

## Development

```bash
npm run watch          # rebuild assets on change
npm run dev-server     # webpack dev server
```

Symfony profiler and web debug toolbar are enabled in the `dev` environment.

## API Overview

The application uses host-based routing — panel API and registration flow are served from separate (sub)domains configured via `FHP_DOMAIN_RESTRICTION` and `REGISTER_DOMAIN_RESTRICTION`.

### Authentication

```
POST /panel/auth/login
GET  /panel/logout
```

### Clients

```
POST   /client/user/create
PUT    /client/user/update
DELETE /client/user/delete
PATCH  /client/company
GET    /client/licenseDetails?subdomain=X&locale=en
```

### Licenses & Orders

```
GET  /api/license
GET  /api/license/set
POST /api/license/order
POST /api/license/order/upgrade
POST /api/license/order/addons
PUT  /api/license/addons
GET  /api/license/order/{orderId}
GET  /api/license/order/invoice
GET  /api/license/order/invoice/{orderId}
```

### Payments

```
GET  /api/payu/data
POST /webhook/payment/payu
```

### Registration

```
GET|POST /register-first-step
GET|POST /{type}/register-second-step
GET      /confirm?confirmationToken=X
GET      /resend?email=X
```

Full OpenAPI docs available at `/doc` (requires login).

## Project Structure

```
src/
├── ClientContext/     # Client registration, accounts, user management
├── LicenseContext/    # License products, add-ons, translations
├── OrderContext/      # Orders, payments, invoices
├── MerchantContext/   # Merchant types, promo codes
└── Common/            # Auth, shared entities, services, PDF generation
```

Architecture follows Domain-Driven Design with CQRS (Commands/Queries via Symfony Messenger).

## Deployment

Deployment is handled via GitLab CI (`.gitlab-ci.yml`) on push to `develop`:

1. `composer install`
2. `npm run build`
3. `php bin/console cache:clear`
4. `php bin/console doctrine:migrations:migrate`

Kubernetes/Helm charts are available in `deploy/k8s-chart/` for production orchestration.
