<?php

namespace App\Reports;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Warehouse;

class EngagementReport
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

    public function users()
    {
        $data['totalUsers'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support'))->count();

        $data['enabledUsers'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->enabled()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support'))->count();

        $data['disabledUsers'] = $data['totalUsers'] - $data['enabledUsers'];

        $data['onricaSupportUsers'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->where('name', 'onrica support'))->count();

        $data['activeUsersYesterday'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()->yesterday()))->count();

        $data['activeUsersToday'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->count();

        $data['activeUsersInLast_7Days'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeUsersInLast_30Days'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        return $data;
    }

    public function branches()
    {
        $data['totalBranches'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->count();

        $data['enabledBranches'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->active()->count();

        $data['disabledBranches'] = $data['totalBranches'] - $data['enabledBranches'];

        $data['activeBranchesYesterday'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()->yesterday()))->count();

        $data['activeBranchesToday'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->count();

        $data['activeBranchesInLast_7Days'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeBranchesInLast_30Days'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        return $data;
    }

    public function companies()
    {
        $data['activeCompanies'] = Employee::whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', $this->filters['user_period'][0])->whereDate('last_online_at', '<=', $this->filters['user_period'][1]))->distinct('company_id')->count();

        $data['companies'] = Company::enabled()
            ->when(!empty($this->filters['company_id']), fn($q) => $q->where('id', $this->filters['company_id']))
            ->withCount([
                'employees' => fn($q) => $q->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', $this->filters['user_period'][0])->whereDate('last_online_at', '<=', $this->filters['user_period'][1])),
                'warehouses' => fn($q) => $q->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', $this->filters['user_period'][0])->whereDate('last_online_at', '<=', $this->filters['user_period'][1])),
            ])
            ->orderBy('employees_count', 'DESC')
            ->orderBy('warehouses_count', 'DESC')
            ->get();

        return $data;
    }

    public function getBranchesWithUserCount()
    {
        return Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->withCount(['originalUsers' => fn($q) => $q->whereNot('name', 'onrica support')])->get(['name', 'original_users_count']);
    }
}
