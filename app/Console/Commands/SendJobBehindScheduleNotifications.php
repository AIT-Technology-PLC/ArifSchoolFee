<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Notifications\JobBehindSchedule;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendJobBehindScheduleNotifications extends Command
{
    protected $signature = 'job:behind-schedule-notification';

    protected $description = 'Send behind schedule job notifications.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $companies = Company::enabled()->get(['id']);

        if ($companies->isEmpty()) {
            return 0;
        }

        foreach ($companies as $company) {
            $jobs = Job::query()
                ->where('company_id', $company->id)
                ->get()
                ->filter(function ($job) {
                    return $job->forecastedCompletionRate > $job->jobCompletionRate;
                });

            if ($jobs->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->permission('Read Job')
                ->where(function ($query) use ($jobs) {
                    $query->whereNull('warehouse_id')
                        ->orWhereIn('warehouse_id', $jobs->pluck('warehouse_id'));
                })
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new JobBehindSchedule($jobs));
        }

        return 0;
    }
}
