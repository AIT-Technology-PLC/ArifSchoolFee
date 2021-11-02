<p align="center">
	<img src="https://onricatech.com/img/logo.png" width="200" />
</p>

## About Onrica SmartWork

**Onrica SmartWork** is a multi-tenant ERP solution.

With its number of modules & features, **Onrica SmartWork** allows businesses to achieve a high level of synchronization among departments and units of a business.

##### Characteristics:

-   Could be deployed as on Cloud/SaaS or on Premise
-   Monolithic Architecture
-   Full Multi-tenant Architecture (i.e. single database and single app instance)
-   PWA App

## Frameworks & Tools Used

At its core [**Onrica SmartWork**](https://onricatech.com/products/smartwork) uses [**Laravel**](https://laravel.com) as a fullstack framework.

**Frontend Tools**

-   Bulma
-   Font Awesome Icons
-   jQuery
-   AlpineJS
-   Axios
-   jQuery DataTables
-   Summernote Text Editor
-   Select2 Dropdown
-   Sweetalert
-   Workbox

**Backend Tools**

-   Laravel
-   Livewire
-   Laravel DomPDF
-   Doctrine Dbal
-   Spatie - Laravel Permission
-   Yajra - Laravel DataTables
-   Laravel Debugbar

## Requirements

-   PHP >= 7.3
-   Composer
-   MySQL
-   [PHP extensions required by Laravel](https://laravel.com/docs/8.x/deployment#server-requirements "PHP extensions required by Laravel")

## Installation Steps

```bash
git clone https://github.com/onrica/ims.git
cp .env.example .env
php artisan key:generate
```

Go to the root folder, find .env file and set values of: DB_DATABASE, DB_USERNAME, and DB_PASSWORD.

```bash
php artisan migrate --seed
php artisan serve
```

## Login Credentials

**Email**
-- admin@onrica.com
**Password**
-- password
