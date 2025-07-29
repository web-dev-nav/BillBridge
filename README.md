# BillBridge - Invoice & Payment Management System

BillBridge is a comprehensive Laravel-based invoice and payment management system built with Filament admin panels. It provides both admin and client portals for managing invoices, quotes, payments, and client relationships.

## Features

- **Admin Panel**: Complete invoice and payment management system
- **Client Portal**: Client-facing dashboard for viewing invoices and making payments
- **Multi-language Support**: Available in multiple languages (English, Arabic, French, German, Spanish, Portuguese, Russian, Turkish, Chinese)
- **Payment Gateways**: Supports Stripe, PayPal, Razorpay, Paystack, and MercadoPago
- **PDF Generation**: Custom invoice and quote templates with PDF export
- **Recurring Invoices**: Automated recurring invoice generation
- **QR Code Payments**: QR code generation for payment links
- **Email Notifications**: Automated email notifications for invoices and payments
- **Export Functionality**: Excel export for invoices, payments, and transactions

## Technology Stack

- **Backend**: Laravel 11.31 with PHP 8.2+
- **Admin Panel**: Filament 3.2
- **Frontend**: Vite, TailwindCSS, Alpine.js
- **Database**: MySQL/PostgreSQL with migrations
- **Payment Processing**: Multiple gateway integrations
- **PDF Generation**: DomPDF
- **Testing**: PestPHP

## Directory Structure

```
BillBridge/
├── app/                          # Core application code
│   ├── Console/                  # Artisan commands
│   │   ├── Commands/            # Custom console commands
│   │   └── Kernel.php           # Console kernel
│   ├── Exports/                  # Excel export classes
│   ├── Filament/                # Filament admin panels
│   │   ├── Client/              # Client portal resources
│   │   ├── Clusters/            # Resource clusters (Settings, Countries)
│   │   ├── Pages/               # Custom admin pages
│   │   ├── Resources/           # Admin panel resources
│   │   └── Widgets/             # Dashboard widgets
│   ├── Forms/                    # Custom form components
│   ├── Http/                     # HTTP layer
│   │   ├── Controllers/         # Application controllers
│   │   ├── Middleware/          # Custom middleware
│   │   └── Responses/           # Custom responses
│   ├── Jobs/                     # Queue jobs
│   ├── Livewire/                # Livewire components
│   ├── Mail/                     # Mail classes
│   ├── Models/                   # Eloquent models
│   ├── Notifications/           # Notification classes
│   ├── Providers/               # Service providers
│   ├── Repositories/            # Repository pattern implementation
│   ├── Utils/                    # Utility classes
│   └── helpers.php              # Global helper functions
├── bootstrap/                    # Application bootstrap
├── config/                       # Configuration files
├── database/                     # Database files
│   ├── factories/               # Model factories
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── infy_invoices.sql        # Database dump
├── docs/                         # Documentation and assets
│   ├── assets/                  # Documentation assets
│   └── images/                  # Screenshots and images
├── lang/                         # Localization files
├── public/                       # Public web files
│   ├── assets/                  # Public assets
│   ├── css/                     # Compiled CSS
│   ├── js/                      # Compiled JavaScript
│   └── images/                  # Public images
├── resources/                    # Raw application resources
│   ├── css/                     # Source CSS files
│   ├── js/                      # Source JavaScript files
│   └── views/                   # Blade templates
├── routes/                       # Route definitions
├── storage/                      # Storage files
│   ├── app/                     # Application storage
│   ├── countries/               # Country/state/city data
│   ├── currencies/              # Currency data
│   └── framework/               # Framework storage
└── tests/                        # Application tests
```

## Key Components

### Models
- **Invoice**: Invoice management with items and taxes
- **Quote**: Quote system with conversion to invoices
- **Client**: Client management with authentication
- **Payment**: Payment tracking and gateway integration
- **Product**: Product catalog management
- **Transaction**: Transaction history and reporting

### Admin Features
- Dashboard with analytics and widgets
- Invoice and quote management
- Client relationship management
- Payment processing and tracking
- Product and category management
- Tax and currency configuration
- Multi-template PDF generation
- Bulk operations and exports

### Client Portal
- Client dashboard with overview
- Invoice viewing and payment
- Transaction history
- Quote management
- Profile management

## Installation

1. Clone the repository
2. Install PHP dependencies: `composer install`
3. Install Node dependencies: `npm install`
4. Configure environment variables
5. Run migrations: `php artisan migrate`
6. Seed the database: `php artisan db:seed`
7. Build assets: `npm run build`

## Default Admin Credentials

After seeding the database, you can login to the admin panel with:

- **Email**: `admin@billbridge.com`
- **Password**: `123456`

**Admin Panel**: `http://localhost:8000/admin`  
**Client Panel**: `http://localhost:8000/client`

## Development

- Start development server: `composer run dev`
- Run tests: `php artisan test`
- Code formatting: `./vendor/bin/pint`

## Payment Gateways

The system supports multiple payment gateway integrations:
- **Stripe**: Credit card processing
- **PayPal**: PayPal payments
- **Razorpay**: Indian payment gateway
- **Paystack**: African payment gateway
- **MercadoPago**: Latin American payments

## License

This project is licensed under the MIT License.
