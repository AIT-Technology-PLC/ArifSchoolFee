<?php

namespace App\Providers;

use App\Models\CustomField;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Yajra\DataTables\EloquentDataTable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../Helpers/Global.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultSimpleView('pagination::simple-default');

        EloquentDataTable::macro('customColumns', function ($modelType) {
            $customFields = CustomField::active()->where('model_type', $modelType)->get();

            foreach ($customFields as $customField) {
                static::editColumn($customField->label, fn($model) => $model->customFieldValue($customField->id));
            }

            return static::addIndexColumn();
        });
    }
}
