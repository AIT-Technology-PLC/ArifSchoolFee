<?php

namespace App\Reports\Admin;

use App\Models\Company;

class SubscriptionReport
{
    private $filters;

    function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getTotalSubscriptionsThisMonth()
    {
        return Company::activeSubscriptions()->whereMonth('subscription_expires_on', today()->month)->count();
    }

    public function getTotalSubscriptionsNextMonth()
    {
        return Company::activeSubscriptions()->whereMonth('subscription_expires_on', today()->addMonth()->month)->count();
    }

    public function getSubscriptionsThisAndNextMonth()
    {
        return Company::activeSubscriptions()->orderBy('subscription_expires_on', 'ASC')->get(['name', 'subscription_expires_on']);
    }

    public function getExpiredSubscriptions()
    {
        return Company::expiredSubscriptions()->orderBy('subscription_expires_on', 'ASC')->get(['name', 'subscription_expires_on']);
    }

    public function getSubscriptionsCountByMonths()
    {
        return Company::activeSubscriptions()
            ->groupByRaw('MONTH(subscription_expires_on), MONTHNAME(subscription_expires_on)')
            ->selectRaw('MONTH(subscription_expires_on) AS month_in_number, MONTHNAME(subscription_expires_on) AS month, COUNT(id) AS count')
            ->orderBy('month_in_number', 'ASC')
            ->pluck('count', 'month_in_number');
    }

    public function getFilteredSubscriptions()
    {
        return Company::query()
            ->whereBetween('subscription_expires_on', [$this->filters['subscription_period'][0], $this->filters['subscription_period'][1]])
            ->orderBy('subscription_expires_on', 'ASC')
            ->get(['name', 'subscription_expires_on']);
    }
}
