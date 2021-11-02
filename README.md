<br/>

<p>
	<img src="https://onricatech.com/img/logo.png" width="200" />
</p>

<br/>

## About Onrica SmartWork

**Onrica SmartWork** is a multi-tenant ERP solution.

With its number of modules & features, **Onrica SmartWork** allows businesses to achieve a high level of synchronization among departments and units of a business.

##### Characteristics:

-   Monolithic Architecture
-   Full Multi-tenant Architecture (i.e. single database and single app instance)
-   PWA App

## Frameworks & Tools Used

At its core [**Onrica SmartWork**](https://onricatech.com/products/smartwork) uses [**Laravel**](https://laravel.com) as a fullstack framework.

| Frontend Tools     | Backend Tools              |
| ------------------ | -------------------------- |
| Bulma              | Laravel                    |
| Font Awesome Icons | Livewire                   |
| jQuery             | Laravel DomPDF             |
| AlpineJS           | Doctrine Dbal              |
| Axios              | Spaite Laravel Permission  |
| jQuery DataTables  | Yajra - Laravel DataTables |
| Summernote Editor  | Laravel Debugbar           |
| Select2 Dropdown   |                            |
| Sweetalert         |                            |
| Workbox            |                            |

## Branches

-   ims/main
    -   Should always be the same branch as the production server
    -   Do not fork this branch
    -   Do not submit PR to this branch
-   ims/dev
    -   Always use this branch for development and making changes
    -   Could be forked
    -   Always submit PRs to this branch

## Requirements

-   PHP >= 7.3
-   Composer
-   MySQL
-   [PHP extensions required by Laravel](https://laravel.com/docs/8.x/deployment#server-requirements "PHP extensions required by Laravel")

## Installation

```bash
git clone https://github.com/onrica/ims.git
cd ims
composer install
cp .env.example .env
php artisan key:generate
```

Go to the root folder, find .env file and set values of: DB_DATABASE, DB_USERNAME, and DB_PASSWORD.

```bash
php artisan migrate --seed
php artisan serve
```

## Login Credentials

| Email            | Password |
| ---------------- | -------- |
| admin@onrica.com | password |
