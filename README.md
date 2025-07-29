# BillBridge - Invoice Management System

This is the Invoice Management System where users can manage all the invoices in one place and digitally.

The client will get its separate login panel from where he can see the lists of his invoices and do payments.

## Multi-Languages Support
We are supporting 9 languages in each panel (Admin | Client):
- English
- Spanish
- French
- German
- Russian
- Portuguese
- Arabic
- Chinese
- Turkish

## Admin Panel

### Dashboard
Powerful admin dashboard where admin can see the overview of the application, what's going on, what are the revenue, etc.

### Clients
You can create a client from the admin panel, and yes of course clients will get their separate panel, where they can see his assigned/sent invoices and perform related actions.

### Products
You can create your products from where, which will be later used in invoice creation. You can define the price, set image, and related information.

### Product Categories
You can create different kinds of product categories which will be used in product creation, you can choose categories while creating products.

### Taxes
You can create different kinds of taxes from here, e.g GST / IGST / etc with related percentages. Taxes will be used while creating the invoice.

### Transactions
All the invoices transactions will be listed here, either it will be manually or via stripe. you can see the detailed information here.

### Settings
You can manage to generate settings from here, like the app logo, favicon the currency, and company address.

Also, you can manage the number format and decimal separator settings from here.

### Invoice Template
We are supporting the beautiful invoice template, which is used when you print the invoice template. You can manage the downloaded invoice format by using this interface.

### Multi Currency
You can add your currency here, whatever you want to show before the price everywhere. The selected currency will be reflected everywhere in the project.

### Multi-Lingual
It comes up with 9 different languages and multi-currency options.

## Client Panel

### Dashboard
Attractive and Powerful dashboard from where the client can see the overview of his invoices, how many invoices are still pending or paid, etc.

### Invoices
The client can see only assigned invoices, he can print invoices and check details of them. Also, clients can pay the invoice manually or via the stripe payment gateway.

### Pay Invoice
Clients can pay invoices in multiple ways. We are supporting the partial payment option, so let's say there is a $1000 invoice and the client wants to pay just $500 then he can choose Partial payment while paying the invoice.

Later when the client does the next payment he will just see $500 remains as he already paid $500.

### Print Invoices
Clients can print the invoices from the details screen, we are supporting the attractive invoices templates.

### Transactions
Here the client can see his invoices payment history and reports, whatever transaction he made will be recorded here.

## Payment Gateways
We are supporting the stripe and PayPal payment gateway to pay invoices from the customer side.

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
