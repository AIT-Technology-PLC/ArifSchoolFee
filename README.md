## About A Project Under AIT TECHNOLOGY PLC

**Multi School Centralized Payment Systsem** is a multi-tenant Payment solution.

With its number of modules & features, **Multi Schhool Centralized Payment** allows businesses to achieve a high level of synchronization among the different brances and units of a business.

##### Characteristics:

-   Monolithic Architecture
-   Full Multi-tenant Architecture (i.e. single database and single app instance)
-   PWA App

## Frameworks & Tools

At its core [**Multi Schhool Centralized Payment**](https://github.com/mikiyasleul/multi_school_payment_system.git) uses [**Laravel**](https://laravel.com) as a fullstack framework.

| Stack       | Frontend Tools     | Backend Tools                            | Other Tools                         |
| ----------- | ------------------ | ---------------------------------------- | ----------------------------------- |
| MySQL       | Bulma              | Laravel                                  | LaraBug - Error Monitoring          |
| PHP Laravel | Font Awesome Icons | Livewire                                 | Google Analytics - Traffic Analysis |
| Alpine.js   | jQuery             | Laravel DomPDF                           |                                     |
| Bulma CSS   | AlpineJS           | Doctrine Dbal                            |                                     |
|             | Axios              | Spatie - Laravel Permission              |                                     |
|             | jQuery DataTables  | Yajra - Laravel DataTables               |                                     |
|             | Summernote Editor  | Laravel Debugbar                         |                                     |
|             | Select2 Dropdown   | Flysystem Google Drive                   |                                     |
|             | Sweetalert         | Spatie - Laravel Backup                  |                                     |
|             | Pace.js            | Laravel Cascade Softdeletes              |                                     |
|             | Workbox            | Maatwebsite - Laravel Excel              |                                     |
|             | Date Range Picker  | Laravel Notifications Channel - Web Push |                                     |
|             | Steps Component    | laravel-id-generator |                                     |

## Branches & Environments

-   main
    -   This is the branch that is running on the production server
    -   Do not submit PR to this branch
-   dev
    -   Always use this branch for development and making changes
    -   Always submit PRs to this branch

## Requirements

-   PHP >= 8.2
-   RAM >= 2GB
-   Composer
-   MySQL or MariaDB
-   [PHP extensions required by Laravel](https://laravel.com/docs/9.x/deployment#server-requirements)
-   [OPTIONAL] To make use of PWA capabilities, install browsers that support **Service Workers** and **Add To Home Screen** from the links below:
    -   [Browsers that support Service Workers](https://caniuse.com/?search=service%20worker)
    -   [Browsers that support website installation](https://caniuse.com/?search=a2hs)
    -   **Recommended Browser: Chrome (both on mobile and desktop)**

## Installation

1. Clone repository & install dependencies

```bash
git clone https://github.com/mikiyasleul/multi_school_payment_system.git
cd multi_school_payment_system
composer install
cp .env.example .env
php artisan key:generate
```

2. Go to your database admin (e.g. phpMyAdmin), and create a database user if you don't already have one
3. Go to your database admin (e.g. phpMyAdmin), and create a database
4. Go to the root folder (i.e. erp), find .env file and set the values of the following: DB_DATABASE, DB_USERNAME, and DB_PASSWORD.

5. Migrate the database, link the storage folder, and you are up and running

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Admin Login Credentials

| User Type | Email            | Password      |
| --------- | ---------------- | ------------- |
| ADMIN     | admin@ait-tech.com | adminpassword |
| USER      | user@ait-tech.com  | userpassword  |
