<?php

namespace App\Actions;

use App\Actions\CreateUserAction;
use App\Models\Company;
use App\Models\Warehouse;

class CreateSchoolAction
{
    private $action;

    public function __construct(CreateUserAction $action)
    {
        $this->action = $action;
    }

    public function execute($data)
    {
        $company = Company::create([
            'name' => $data['company_name'],
            'currency' => 'ETB',
            'enabled' => 0,
        ]);

        $subscription = $company->subscriptions()->create($data['subscriptions']);

        $subscription->approve();

        $warehouse = Warehouse::create([
            'company_id' => $company->id,
            'name' => 'Main Branch',
            'location' => 'Unknown',
            'is_active' => 1,
        ]);

        $user = $this->action->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'warehouse_id' => $warehouse->id,
            'enabled' => 1,
            'position' => 'AIT Support Team',
            'role' => 'System Manager',
            'gender' => 'male',
            'address' => 'Addis Ababa, Ethiopia',
            'job_type' => 'remote',
            'phone' => '0966020599',
        ]);

        $user->employee->company()->associate($company)->save();

        $warehouse->createdBy()->associate($user)->save();

        $warehouse->updatedBy()->associate($user)->save();

        return $user;
    }
}
