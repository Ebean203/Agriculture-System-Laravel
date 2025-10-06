<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Agriculture System Laravel Project

## Project Overview
The Agriculture System is a Laravel-based web application designed to manage agricultural activities, farmer records, input distributions, yield monitoring, and more. It provides a user-friendly interface for administrators and staff to oversee and manage agricultural operations efficiently.

---

## Directory Structure
Below is a breakdown of the main directories and their purposes, along with the files they contain:

- **`app/`**: Contains the core application logic, including models, controllers, and service providers.
  - **`Http/Controllers/`**: Houses controllers that handle application logic.
    - `ActivityLogController.php`: Handles fetching and displaying activity logs.
    - `FarmerController.php`: Manages farmer-related operations.
    - `ReportController.php`: Generates and manages reports.
    - `Auth/`: Contains authentication-related controllers.
  - **`Models/`**: Contains Eloquent models representing database tables.
    - `Farmer.php`: Represents the `farmers` table.
    - `ActivityLog.php`: Represents the `activity_logs` table.
    - `User.php`: Represents the `users` table.
  - **`Providers/`**: Includes service providers for application bootstrapping.
    - `AppServiceProvider.php`: Registers application services.
    - `AuthServiceProvider.php`: Handles authentication services.

- **`bootstrap/`**: Contains files for bootstrapping the Laravel framework.
  - `app.php`: Initializes the application.

- **`config/`**: Stores configuration files for the application.
  - `app.php`: Core application configuration.
  - `database.php`: Database connection settings.
  - `mail.php`: Mail configuration.

- **`database/`**: Includes database migrations, factories, and seeders.
  - `migrations/`: Contains migration files for database schema.
  - `seeders/`: Includes seeders for populating test data.

- **`public/`**: The web server's document root, containing assets like CSS, JavaScript, and images.
  - `index.php`: Entry point for the application.
  - `css/`: Compiled CSS files.
  - `js/`: Compiled JavaScript files.

- **`resources/`**: Contains views, raw assets (CSS, JS), and localization files.
  - **`views/`**: Blade templates for the frontend.
    - `activities/index.blade.php`: Displays the main activities page with icons and tooltips.
    - `activities/all.blade.php`: Shows all system-wide activity logs with filters and pagination.
    - `yield/monitoring.blade.php`: Provides a yield monitoring interface with farmer search functionality.
  - `css/`: Raw CSS files.
  - `js/`: Raw JavaScript files.

- **`routes/`**: Defines all application routes.
  - `web.php`: Defines web routes for the application.
  - `api.php`: Defines API routes.

- **`storage/`**: Stores logs, compiled Blade templates, and other temporary files.
  - `logs/`: Contains application logs.

- **`tests/`**: Contains unit and feature tests.
  - `Feature/`: Includes feature tests.
  - `Unit/`: Contains unit tests.

- **`vendor/`**: Houses third-party packages installed via Composer.

---

## File Walkthrough
Here is a guide to the key files in the system:

### Root Files
- **`artisan`**: Command-line interface for Laravel.
- **`composer.json`**: Lists PHP dependencies for the project.
- **`package.json`**: Lists JavaScript dependencies.
- **`vite.config.js`**: Configuration for Vite, used for asset bundling.

### Key Files in `resources/views/`
- **`activities/index.blade.php`**: Displays the main activities page with icons and tooltips.
- **`activities/all.blade.php`**: Shows all system-wide activity logs with filters and pagination.
- **`yield/monitoring.blade.php`**: Provides a yield monitoring interface with farmer search functionality.

### Key Files in `app/Http/Controllers/`
- **`ActivityLogController.php`**: Handles fetching and displaying activity logs.
- **`FarmerController.php`**: Manages farmer-related operations.
- **`ReportController.php`**: Generates and manages reports.

### Key Files in `routes/`
- **`web.php`**: Defines web routes for the application.
- **`api.php`**: Defines API routes.

### Key Files in `config/`
- **`app.php`**: Core application configuration.
- **`database.php`**: Database connection settings.

---

## How to Use the System

### Running the Application
1. Start the development server:
   ```bash
   php artisan serve
   ```
2. Access the application at `http://localhost:8000`.

### Clearing Caches
Run the following commands to clear and rebuild caches:
```bash
php artisan route:clear ; php artisan route:cache ; php artisan view:clear
```

### Running Migrations
To set up the database:
```bash
php artisan migrate
```

### Seeding the Database
To populate the database with test data:
```bash
php artisan db:seed
```

---

## Troubleshooting and Debugging

### Common Issues
- **Routes not working**: Clear and rebuild the route cache.
- **View changes not reflecting**: Clear the view cache.
- **Database errors**: Ensure migrations are run and the `.env` file is correctly configured.

### Tracking Files
Use this guide to locate files quickly when debugging or making changes. For example:
- To update the Activities page, edit `resources/views/activities/index.blade.php`.
- To modify farmer-related logic, check `app/Http/Controllers/FarmerController.php`.

---

## Additional Notes
- Always ensure the `.env` file is correctly configured for your environment.
- Use `php artisan tinker` for quick debugging and testing.

---

## Project Directory Tree

Below is a concise directory tree of the repository to help you navigate (top-level and key subfolders/files). Some vendor and cache content is omitted for brevity.

```
.
├─ .editorconfig
├─ .env (local)
├─ .env.example
├─ artisan
├─ composer.json
├─ composer.lock
├─ package.json
├─ package-lock.json
├─ phpunit.xml
├─ postcss.config.js
├─ tailwind.config.js
├─ vite.config.js
├─ README.md
├─ Agriculture-System/              # legacy procedural PHP folder (migrating to Laravel)
├─ app/
│  ├─ Http/
│  │  ├─ Controllers/
│  │  │  ├─ ActivitiesController.php
│  │  │  ├─ ActivityLogController.php
│  │  │  ├─ AnalyticsController.php
│  │  │  ├─ Auth/ (auth controllers)
│  │  │  ├─ BoatsController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ DashboardController.php
│  │  │  ├─ DistributionsController.php
│  │  │  ├─ FarmerController.php
│  │  │  ├─ FishrController.php
│  │  │  ├─ InventoryController.php
│  │  │  ├─ NcfrsController.php
│  │  │  ├─ NotificationController.php
│  │  │  ├─ ProfileController.php
│  │  │  ├─ ReportController.php
│  │  │  ├─ RsbsaController.php
│  │  │  └─ YieldMonitoringController.php
│  │  ├─ Middleware/
│  │  │  ├─ CheckAuth.php
│  │  │  └─ CheckRole.php
│  │  └─ Requests/
│  │     ├─ Auth/ (form requests)
│  │     └─ ProfileUpdateRequest.php
│  ├─ Models/
│  │  ├─ ActivityLog.php
│  │  ├─ Barangay.php
│  │  ├─ Commodity.php
│  │  ├─ CommodityCategory.php
│  │  ├─ Farmer.php
│  │  ├─ FarmerPhoto.php
│  │  ├─ GeneratedReport.php
│  │  ├─ HouseholdInfo.php
│  │  ├─ InputCategory.php
│  │  ├─ MaoDistributionLog.php
│  │  ├─ MaoInventory.php
│  │  ├─ MaoStaff.php
│  │  ├─ Role.php
│  │  ├─ User.php
│  │  └─ YieldMonitoring.php
│  └─ Providers/
├─ bootstrap/
│  └─ app.php
├─ config/
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ dompdf.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database/
│  ├─ factories/
│  ├─ migrations/
│  └─ seeders/
├─ public/
│  ├─ index.php
│  ├─ favicon.ico
│  ├─ robots.txt
│  ├─ fonts/
│  │  ├─ fa-brands-400.woff2
│  │  ├─ fa-regular-400.woff2
│  │  └─ fa-solid-900.woff2
│  ├─ reports/
│  │  └─ report_registration_analytics_2025-10-06_04-02-48.html
│  ├─ uploads/
│  │  └─ farmer_photos/
│  ├─ agriculture-assets/
│  │  ├─ css/
│  │  │  ├─ bootstrap.min.css
│  │  │  ├─ custom.css
│  │  │  ├─ fontawesome.min.css
│  │  │  └─ tailwind-custom.css
│  │  └─ js/
│  │     ├─ bootstrap.bundle.min.js
│  │     ├─ chart.min.js
│  │     ├─ chartjs-plugin-datalabels.min.js
│  │     ├─ jquery.min.js
│  │     └─ tailwind-cdn.js
│  ├─ build/            # Vite build output (used in production)
│  ├─ css/              # Vite build output (if emitted)
│  └─ js/               # Vite build output (if emitted)
├─ resources/
│  ├─ css/
│  │  └─ app.css
│  ├─ js/
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views/
│     ├─ activities/
│     │  ├─ all.blade.php
│     │  └─ index.blade.php
│     ├─ analytics/
│     │  └─ index.blade.php
│     ├─ auth/
│     │  ├─ confirm-password.blade.php
│     │  ├─ forgot-password.blade.php
│     │  ├─ login-agriculture.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ boats/
│     │  └─ index.blade.php
│     ├─ components/
│     │  ├─ agriculture-assets.blade.php
│     │  ├─ application-logo.blade.php
│     │  ├─ auth-session-status.blade.php
│     │  ├─ chart-assets.blade.php
│     │  ├─ danger-button.blade.php
│     │  ├─ dropdown-link.blade.php
│     │  ├─ dropdown.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ modal.blade.php
│     │  ├─ nav-link.blade.php
│     │  ├─ navigation.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ responsive-nav-link.blade.php
│     │  ├─ secondary-button.blade.php
│     │  ├─ sidebar.blade.php
│     │  └─ text-input.blade.php
│     ├─ distributions/
│     │  └─ index.blade.php
│     ├─ farmers/
│     │  ├─ index.blade.php
│     │  └─ pdf.blade.php
│     ├─ fishr/
│     │  └─ index.blade.php
│     ├─ inventory/
│     │  ├─ index.blade.php
│     │  └─ partials/
│     │     ├─ inventory-card.blade.php
│     │     ├─ javascript.blade.php
│     │     └─ modals.blade.php
│     ├─ layouts/
│     │  ├─ agriculture.blade.php
│     │  ├─ app.blade.php
│     │  └─ guest.blade.php
│     ├─ modals/
│     │  ├─ distribute-modal.blade.php
│     │  ├─ farmer-edit-modal.blade.php
│     │  ├─ farmer-modal.blade.php
│     │  ├─ farmer-view-modal.blade.php
│     │  ├─ geo-tagging-modal.blade.php
│     │  └─ yield-modal.blade.php
│     ├─ ncfrs/
│     │  └─ index.blade.php
│     ├─ profile/
│     │  ├─ edit.blade.php
│     │  └─ partials/
│     ├─ reports/
│     │  ├─ all.blade.php
│     │  ├─ index.blade.php
│     │  ├─ layouts/
│     │  │  └─ print.blade.php
│     │  └─ templates/
│     │     ├─ barangay_analytics.blade.php
│     │     ├─ commodity_production.blade.php
│     │     ├─ comprehensive_overview.blade.php
│     │     ├─ farmers_summary.blade.php
│     │     ├─ input_distribution.blade.php
│     │     ├─ inventory_status.blade.php
│     │     ├─ registration_analytics.blade.php
│     │     └─ yield_monitoring.blade.php
│     ├─ rsbsa/
│     │  └─ index.blade.php
│     ├─ staff/
│     │  └─ index.blade.php
│     ├─ yield/
│     │  └─ monitoring.blade.php
│     ├─ coming-soon.blade.php
│     └─ welcome.blade.php
├─ routes/
│  ├─ web.php
│  ├─ api.php
│  └─ console.php
├─ storage/
│  ├─ app/
│  ├─ framework/
│  └─ logs/
├─ tests/
│  ├─ Pest.php
│  ├─ TestCase.php
│  ├─ Feature/
│  └─ Unit/
└─ vendor/
```

## Tools & Libraries Used

This project uses a combination of PHP (Composer) packages and JavaScript (NPM) packages, plus some CDN/local assets.

### PHP (Composer)
- laravel/framework (^12.0)
- laravel/tinker (^2.10)
- barryvdh/laravel-dompdf (^3.1) — PDF generation

Dev-only:
- laravel/breeze — auth scaffolding
- pestphp/pest, pest-plugin-laravel — testing
- laravel/pint — code style
- laravel/sail — Docker dev env (optional)
- mockery/mockery — testing
- fakerphp/faker — factories/seed data
- reliese/laravel — model generation

### JavaScript (NPM)
- bootstrap (^5.3)
- jquery (^3.7)
- chart.js (^4.5)
- axios (^1.11)
- alpinejs (^3.x)
- vite (^7), laravel-vite-plugin (^2)
- tailwindcss (^3), @tailwindcss/forms
- postcss, autoprefixer
- concurrently — local dev convenience

### Frontend Assets (Local/CDN)
- Bootstrap CSS/JS
  - Local: `public/agriculture-assets/css/bootstrap.min.css`, `public/agriculture-assets/js/bootstrap.bundle.min.js`
  - CDN (fallback): jsDelivr links in `resources/views/components/agriculture-assets.blade.php`
- jQuery
  - Local: `public/agriculture-assets/js/jquery.min.js`
  - CDN fallback: code.jquery.com
- Tailwind CSS
  - Vite-built via `resources/css/app.css` and Tailwind config
  - Also a `tailwind-cdn.js` helper for offline/print in reports
- Font Awesome
  - Local: `public/agriculture-assets/css/fontawesome.min.css`
  - CDN fallback: cdnjs link in assets component
- Chart.js and chartjs-plugin-datalabels
  - Local: `public/agriculture-assets/js/chart.min.js`, `public/agriculture-assets/js/chartjs-plugin-datalabels.min.js`
  - Used by analytics/dashboard and other charts
- Vite build pipeline
  - `vite.config.js` and `@vite` directives in Blade templates

## Notes on Legacy Files
- The folder `Agriculture-System/` contains legacy procedural PHP pages. The application is being migrated to Laravel MVC.
- Legacy entry files like `analytics_dashboard.php` and other standalone scripts have been removed or replaced with routes, controllers, and Blade views (e.g., `/analytics` → `AnalyticsController@index` and `resources/views/analytics/index.blade.php`).

## Quick Start (Dev)

```powershell
# Install PHP deps
composer install

# Install JS deps
npm install

# Build assets (dev)
npm run dev

# Run Laravel server
php artisan serve
```

## Caches & Maintenance

```powershell
php artisan route:clear ; php artisan route:cache ; php artisan view:clear
```

If you switch routes or views, clear caches to reflect changes.

---

This README serves as a comprehensive guide to understanding and navigating the Agriculture System Laravel project. For further assistance, refer to the Laravel documentation.

### Full Used Directories and Files (Detailed)

The list below includes only directories/files that exist in this system and are used by features (controllers, views, assets, auth, reports, etc.).

```
app/
├─ Http/
│  ├─ Controllers/
│  │  ├─ ActivitiesController.php
│  │  ├─ ActivityLogController.php
│  │  ├─ AnalyticsController.php
│  │  ├─ BoatsController.php
│  │  ├─ DashboardController.php
│  │  ├─ DistributionsController.php
│  │  ├─ FarmerController.php
│  │  ├─ FishrController.php
│  │  ├─ InventoryController.php
│  │  ├─ NcfrsController.php
│  │  ├─ NotificationController.php
│  │  ├─ ProfileController.php
│  │  ├─ ReportController.php
│  │  ├─ RsbsaController.php
│  │  └─ YieldMonitoringController.php
│  ├─ Middleware/
│  │  ├─ CheckAuth.php
│  │  └─ CheckRole.php
│  └─ Requests/
│     ├─ Auth/
│     └─ ProfileUpdateRequest.php
├─ Models/
│  ├─ ActivityLog.php
│  ├─ Barangay.php
│  ├─ Commodity.php
│  ├─ CommodityCategory.php
│  ├─ Farmer.php
│  ├─ FarmerPhoto.php
│  ├─ GeneratedReport.php
│  ├─ HouseholdInfo.php
│  ├─ InputCategory.php
│  ├─ MaoDistributionLog.php
│  ├─ MaoInventory.php
│  ├─ MaoStaff.php
│  ├─ Role.php
│  ├─ User.php
│  └─ YieldMonitoring.php
├─ Providers/
│  └─ AppServiceProvider.php
├─ View/
│  └─ Components/
│     ├─ AgricultureAssets.php
│     ├─ AppLayout.php
│     └─ GuestLayout.php

bootstrap/
└─ app.php

config/
├─ app.php
├─ auth.php
├─ cache.php
├─ database.php
├─ dompdf.php
├─ filesystems.php
├─ logging.php
├─ mail.php
├─ queue.php
├─ services.php
└─ session.php

database/
├─ factories/
│  └─ UserFactory.php
├─ migrations/
│  ├─ 0001_01_01_000000_create_users_table.php
│  ├─ 0001_01_01_000001_create_cache_table.php
│  └─ 0001_01_01_000002_create_jobs_table.php
└─ seeders/
   └─ DatabaseSeeder.php

public/
├─ index.php
├─ favicon.ico
├─ robots.txt
├─ fonts/
│  ├─ fa-brands-400.woff2
│  ├─ fa-regular-400.woff2
│  └─ fa-solid-900.woff2
├─ reports/
│  └─ report_registration_analytics_2025-10-06_04-02-48.html
├─ uploads/
│  └─ farmer_photos/
├─ agriculture-assets/
│  ├─ css/
│  │  ├─ bootstrap.min.css
│  │  ├─ custom.css
│  │  ├─ fontawesome.min.css
│  │  └─ tailwind-custom.css
│  └─ js/
│     ├─ bootstrap.bundle.min.js
│     ├─ chart.min.js
│     ├─ chartjs-plugin-datalabels.min.js
│     ├─ jquery.min.js
│     └─ tailwind-cdn.js
├─ build/            # Vite build output (used in production)
├─ css/              # Vite build output (if emitted)
└─ js/               # Vite build output (if emitted)

resources/
├─ css/
│  └─ app.css
├─ js/
│  ├─ app.js
│  └─ bootstrap.js
└─ views/
   ├─ activities/
   │  ├─ all.blade.php
   │  └─ index.blade.php
   ├─ analytics/
   │  └─ index.blade.php
   ├─ auth/
   │  ├─ confirm-password.blade.php
   │  ├─ forgot-password.blade.php
   │  ├─ login-agriculture.blade.php
   │  ├─ login.blade.php
   │  ├─ register.blade.php
   │  ├─ reset-password.blade.php
   │  └─ verify-email.blade.php
   ├─ boats/
   │  └─ index.blade.php
   ├─ components/
   │  ├─ agriculture-assets.blade.php
   │  ├─ application-logo.blade.php
   │  ├─ auth-session-status.blade.php
   │  ├─ chart-assets.blade.php
   │  ├─ danger-button.blade.php
   │  ├─ dropdown-link.blade.php
   │  ├─ dropdown.blade.php
   │  ├─ input-error.blade.php
   │  ├─ input-label.blade.php
   │  ├─ modal.blade.php
   │  ├─ nav-link.blade.php
   │  ├─ navigation.blade.php
   │  ├─ primary-button.blade.php
   │  ├─ responsive-nav-link.blade.php
   │  ├─ secondary-button.blade.php
   │  ├─ sidebar.blade.php
   │  └─ text-input.blade.php
   ├─ distributions/
   │  └─ index.blade.php
   ├─ farmers/
   │  ├─ index.blade.php
   │  └─ pdf.blade.php
   ├─ fishr/
   │  └─ index.blade.php
   ├─ inventory/
   │  ├─ index.blade.php
   │  └─ partials/
   │     ├─ inventory-card.blade.php
   │     ├─ javascript.blade.php
   │     └─ modals.blade.php
   ├─ layouts/
   │  ├─ agriculture.blade.php
   │  ├─ app.blade.php
   │  └─ guest.blade.php
   ├─ modals/
   │  ├─ distribute-modal.blade.php
   │  ├─ farmer-edit-modal.blade.php
   │  ├─ farmer-modal.blade.php
   │  ├─ farmer-view-modal.blade.php
   │  ├─ geo-tagging-modal.blade.php
   │  └─ yield-modal.blade.php
   ├─ ncfrs/
   │  └─ index.blade.php
   ├─ profile/
   │  ├─ edit.blade.php
   │  └─ partials/
   ├─ reports/
   │  ├─ all.blade.php
   │  ├─ index.blade.php
   │  ├─ layouts/
   │  │  └─ print.blade.php
   │  └─ templates/
   │     ├─ barangay_analytics.blade.php
   │     ├─ commodity_production.blade.php
   │     ├─ comprehensive_overview.blade.php
   │     ├─ farmers_summary.blade.php
   │     ├─ input_distribution.blade.php
   │     ├─ inventory_status.blade.php
   │     ├─ registration_analytics.blade.php
   │     └─ yield_monitoring.blade.php
   ├─ rsbsa/
   │  └─ index.blade.php
   ├─ staff/
   │  └─ index.blade.php
   ├─ yield/
   │  └─ monitoring.blade.php
   ├─ coming-soon.blade.php
   └─ welcome.blade.php

routes/
├─ web.php
├─ api.php
└─ console.php

tests/
├─ Pest.php
├─ TestCase.php
├─ Feature/
└─ Unit/

vendor/ (Composer packages)
```
