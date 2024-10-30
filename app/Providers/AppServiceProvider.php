<?php

namespace App\Providers;

use App\Listeners\TransferEventSubscriber;
use App\Models\CustomField;
use App\Models\Returnn;
use App\Policies\ReturnPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Yajra\DataTables\EloquentDataTable;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once __DIR__ . '/../Helpers/Global.php';
    }

    public function boot()
    {
        Paginator::defaultSimpleView('pagination::simple-default');

        EloquentDataTable::macro('customColumns', function ($modelType) {
            $customFields = CustomField::active()->visibleOnColumns()->where('model_type', $modelType)->get();

            foreach ($customFields as $customField) {
                static::editColumn($customField->label, fn($model) => $model->customFieldValue($customField->id));
            }

            return static::addIndexColumn();
        });
    }
}
