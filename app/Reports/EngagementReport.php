<?php

namespace App\Reports;

use App\Models\Employee;
use App\Models\Warehouse;

class EngagementReport
{
    private $company;

    function __construct($company = null)
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
        $data['totalUsers'] = Employee::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support'))->count();

        $data['enabledUsers'] = Employee::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->enabled()->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support'))->count();

        $data['disabledUsers'] = $data['totalUsers'] - $data['enabledUsers'];

        $data['onricaSupportUsers'] = Employee::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('user', fn($q) => $q->where('name', 'onrica support'))->count();

        $data['activeUsersToday'] = Employee::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->count();

        $data['activeUsersInLast_7Days'] = Employee::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeUsersInLast_30Days'] = Employee::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        return $data;
    }

    public function branches()
    {
        $data['totalBranches'] = Warehouse::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->count();

        $data['enabledBranches'] = Warehouse::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->active()->count();

        $data['disabledBranches'] = $data['totalBranches'] - $data['enabledBranches'];

        $data['activeBranchesToday'] = Warehouse::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->count();

        $data['activeBranchesInLast_7Days'] = Warehouse::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subWeek()))->count();

        $data['activeBranchesInLast_30Days'] = Warehouse::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', '>=', today()->subDays(30)))->count();

        return $data;
    }

    public function companies()
    {
        $data['activeCompaniesToday'] = Employee::whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today()))->distinct('company_id')->count();

        return $data;
    }
}
