<?php

namespace App\Reports\Admin;

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
        $data['activeUsersToday'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', today()))->count();

        $data['activeUsersYesterday'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', today()->yesterday()))->count();

        $data['activeUsersInLast_7Days'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeUsersInLast_30Days'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        $data['totalUsers'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->whereNot('name', 'ait support'))->count();

        $data['enabledUsers'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->enabled()->whereHas('user', fn($q) => $q->whereNot('name', 'ait support'))->count();

        $data['disabledUsers'] = $data['totalUsers'] - $data['enabledUsers'];

        $data['aitSupportUsers'] = Employee::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('user', fn($q) => $q->where('name', 'ait support'))->count();

        return $data;
    }

    public function branches()
    {
        $data['activeBranchesToday'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', today()))->count();

        $data['activeBranchesYesterday'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', today()->yesterday()))->count();

        $data['activeBranchesInLast_7Days'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeBranchesInLast_30Days'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        $data['totalBranches'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->count();

        $data['enabledBranches'] = Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->active()->count();

        $data['disabledBranches'] = $data['totalBranches'] - $data['enabledBranches'];

        return $data;
    }

    public function companies()
    {
        $data['activeCompanies'] = Employee::whereHas('user', fn($q) => $q->whereNot('name', 'ait support')->whereDate('last_online_at', '>=', $this->filters['user_period'][0])->whereDate('last_online_at', '<=', today()))->distinct('company_id')->count();

        $data['companies'] = Company::enabled()
            ->when(
                !empty($this->filters['company_id']),
                fn($q) => $q->where('id', $this->filters['company_id'])
            )
            ->whereHas('employees', fn($q) => 
                $q->whereHas('user', fn($q) =>
                    $q->whereNot('name', 'ait support')
                    ->whereDate('last_online_at', today())
                )
            )
            ->withCount([
                'employees' => fn($q) =>
                    $q->whereHas('user', fn($q) =>
                        $q->whereNot('name', 'ait support')
                        ->whereDate('last_online_at', '>=', $this->filters['user_period'][0])
                        ->whereDate('last_online_at', '<=', today())
                    ),
                'warehouses' => fn($q) =>
                    $q->whereHas('originalUsers', fn($q) =>
                        $q->whereNot('name', 'ait support')
                        ->whereDate('last_online_at', '>=', $this->filters['user_period'][0])
                        ->whereDate('last_online_at', '<=', today())
                    ),
            ])
            ->orderBy('employees_count', 'DESC')
            ->orderBy('warehouses_count', 'DESC')
            ->get();

        return $data;
    }

    public function getBranchesWithUserCount()
    {
        return Warehouse::when(!empty($this->filters['company_id']), fn($q) => $q->where('company_id', $this->filters['company_id']))->withCount(['originalUsers' => fn($q) => $q->whereNot('name', 'ait support')])->get(['name', 'original_users_count']);
    }
}
