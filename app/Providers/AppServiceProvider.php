<?php

namespace App\Providers;

use App\Models\EmailSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once __DIR__ . '/../Helpers/Global.php';
    }

    public function boot()
    {
        Paginator::defaultSimpleView('pagination::simple-default');

        try {
            $email = EmailSetting::firstOrFail();
    
            $data = [
                'driver' => $email->mail_driver,
                'host' => $email->mail_host,
                'port' => $email->mail_port,
                'encryption' => $email->mail_encryption,
                'username' => $email->mail_username,
                'password' => $email->mail_password, 
                'verify_peer' => false,
                'from' => [
                    'address' => $email->from_mail,
                    'name' => $email->from_name,
                ]
            ];
    
            Config::set('mail', $data);
        } catch (\Exception $e) {
            Log::error('Failed to set mail configuration: ' . $e->getMessage());
        }
    }
}
