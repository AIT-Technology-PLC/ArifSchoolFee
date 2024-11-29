<?php

use App\Models\Feature;
use App\Models\Limit;
use App\Models\Pad;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;


if (!function_exists('authUser')) {
    function authUser(): ?User
    {
        return auth()->user();
    }
}

if (!function_exists('userCompany')) {
    function userCompany()
    {
        return authUser()->employee->company;
    }
}

if (!function_exists('limitReached')) {
    function limitReached($limitName, $currentAmount)
    {
        return (new Limit())->isLimitReached($limitName, $currentAmount);
    }
}

if (!function_exists('isFeatureEnabled')) {
    function isFeatureEnabled(...$featureNames)
    {
        if (!auth()->check()) {
            return false;
        }

        if (authUser()->isAdmin()) {
            return true;
        }

        $key = authUser()->id . '_' . 'enabledFeatures';

        $enabledFeatures = Cache::store('array')->rememberForever($key, function () {
            return Feature::getAllEnabledFeaturesOfCompany();
        });

        return $enabledFeatures->merge(pads()->pluck('name'))->intersect($featureNames)->isNotEmpty();
    }
}

if (!function_exists('isFeatureEnabledForCompany')) {
    function isFeatureEnabledForCompany($companyId, ...$featureNames)
    {
        $enabledFeatures = Cache::store('array')->rememberForever($companyId . 'enabledFeatures', function () use ($companyId) {
            return Feature::getAllEnabledFeaturesOfCompany($companyId);
        });

        return $enabledFeatures->merge(pads()->pluck('name'))->intersect($featureNames)->isNotEmpty();
    }
}

if (!function_exists('money')) {
    function money($amount = 0.00, $currency = null)
    {
        $currency = $currency ?: userCompany()->currency;

        return $currency . '. ' . number_format($amount, 2);
    }
}

if (!function_exists('nextReferenceNumber')) {
    function nextReferenceNumber($table, $column = 'code')
    {
        return DB::table($table)
            ->where('company_id', userCompany()->id)
            ->when(Schema::hasColumn($table, 'warehouse_id'), fn($q) => $q->where('warehouse_id', authUser()->warehouse_id))
            ->max($column) + 1;
    }
}

if (!function_exists('nextInvoiceNumber')) {
    function nextInvoiceNumber($table, $column = 'invoice_number')
    {
        $latestInvoice = DB::table($table)
            ->where('company_id', userCompany()->id)
            ->orderByDesc($column)
            ->first();

        $sequence = 1;
        
        if ($latestInvoice) {
            preg_match('/(\d+)$/', $latestInvoice->$column, $matches);
            if (isset($matches[1])) {
                $sequence = (int) $matches[1] + 1;
            }
        }

        return userCompany()->company_code . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('quantity')) {
    function quantity($amount = 0.00, $unitOfMeasurement = 'Piece')
    {
        if (!$unitOfMeasurement) {
            $unitOfMeasurement = 'Piece';
        }

        return number_format($amount, 2) . ' ' . $unitOfMeasurement;
    }
}

if (!function_exists('pads')) {
    function pads($module = null)
    {
        $key = 'pads';

        if (auth()->check()) {
            $key = authUser()->id . '_' . 'pads';
        }

        $pads = Cache::store('array')->rememberForever($key, function () {
            return Pad::enabled()->get();
        });

        return $pads->when($module, fn($q) => $q->where('module', $module));
    }
}

if (!function_exists('getPadPermissions')) {
    function getPadPermissions()
    {
        return Cache::store('array')->rememberForever(authUser()->id . '_' . 'padPermissions', function () {
            return authUser()->padPermissions()->get();
        });
    }
}

if (!function_exists('dateRangePicker')) {
    function dateRangePicker($period)
    {
        return explode('-', str($period)->replace(' ', ''));
    }
}

if (!function_exists('turnOffPreparedStatementEmulation')) {
    function turnOffPreparedStatementEmulation()
    {
        Config::set('database.connections.mysql.options.' . \PDO::ATTR_EMULATE_PREPARES, true);
    }
}

if (!function_exists('turnOffMysqlStictMode')) {
    function turnOffMysqlStictMode()
    {
        Config::set('database.connections.mysql.strict', false);
    }
}

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $decimals = '';

        if (!is_numeric($number)) {
            return 'N/A';
        }

        $number = number_format($number, 2, thousands_separator: '');

        if (str($number)->after('.')->toString() != '00') {
            $decimals = str($number)->after('.')->append('/100')->prepend(' ')->toString();
        }

        return str(
            (new NumberFormatter('en', NumberFormatter::SPELLOUT))->format((int) $number)
        )->append($decimals);
    }
}

if (!function_exists('carbon')) {
    function carbon($value)
    {
        if (is_null($value) || !strtotime($value)) {
            return null;
        }

        return Carbon::parse($value);
    }
}

if (!function_exists('get_static_option')) {
    function get_static_option($key)
    {
        $value = env($key);

        return $value ? $value : null;
    }
}

if (!function_exists('set_static_option')) {
    function set_static_option($key, $value)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            $content = File::get($path);

            if (strpos($content, $key) !== false) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }

            File::put($path, $content);

            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }
    }
}

if (!function_exists('update_static_option')) {
    function update_static_option($key, $value)
    {
        set_static_option($key, $value);
    }
}