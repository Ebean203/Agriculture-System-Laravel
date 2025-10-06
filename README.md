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

This README serves as a comprehensive guide to understanding and navigating the Agriculture System Laravel project. For further assistance, refer to the Laravel documentation.
