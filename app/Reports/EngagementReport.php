<?php

namespace App\Reports;

class EngagementReport
{
    private $company;

    function __construct($company)
    {
        $this->company = $company;
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function users()
    {
        $data['totalUsers'] = $this->company->employees()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support'))->count();

        $data['enabledUsers'] = $this->company->employees()->enabled()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support'))->count();

        $data['disabledUsers'] = $data['totalUsers'] - $data['enabledUsers'];

        $data['onricaSupportUsers'] = $this->company->employees()->whereHas('user', fn($q) => $q->where('name', 'onrica support'))->count();

        $data['activeUsersToday'] = $this->company->employees()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->count();

        $data['activeUsersInLast_7Days'] = $this->company->employees()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeUsersInLast_30Days'] = $this->company->employees()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        return $data;
    }

    public function branches()
    {
        $data['totalBranches'] = $this->company->warehouses()->count();

        $data['enabledBranches'] = $this->company->warehouses()->active()->count();

        $data['disabledBranches'] = $data['totalBranches'] - $data['enabledBranches'];

        $data['activeBranchesToday'] = $this->company->warehouses()->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->count();

        $data['activeBranchesInLast_7Days'] = $this->company->warehouses()->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeBranchesInLast_30Days'] = $this->company->warehouses()->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        return $data;
    }

    public function realtime()
    {
        $data['activeBranchesToday'] = $this->company->warehouses()->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->where('last_online_at', '>=', now()->subMinutes(30)))->count();

        $data['activeUsersToday'] = $this->company->employees()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->where('last_online_at', '>=', now()->subMinutes(30)))->count();

        return $data;
    }
}
