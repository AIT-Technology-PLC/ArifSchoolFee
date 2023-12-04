<?php

namespace App\Reports;

use App\Models\Company;

class SubscriptionReport
{
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
        return Company::activeSubscriptions()
            ->whereMonth('subscription_expires_on', today()->month)
            ->orWhereMonth('subscription_expires_on', today()->addMonth()->month)
            ->orderBy('subscription_expires_on', 'ASC')
            ->get(['name', 'subscription_expires_on']);
    }
}
