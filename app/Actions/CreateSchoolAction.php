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
            'company_code' => $data['company_code'] ?? 'CODE',
            'school_type_id' => $data['school_type_id'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'enabled' => 0,
            'currency' => 'Br',
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
            'name' => $data['name'] ?? $data['user']['name'],
            'email' => $data['email'] ?? $data['user']['email'],
            'password' => $data['password'] ?? $data['user']['password'],
            'warehouse_id' => $warehouse->id,
            'gender' => $data['gender'] ?? 'male',
            'address' => $data['user']['address'] ?? 'Unknown',
            'phone' => $data['user']['phone'] ?? null,
            'enabled' => 1,
            'position' => 'Admin Support Account',
            'role' => 'System Manager',
        ]);

        $user->employee->company()->associate($company)->save();

        $warehouse->createdBy()->associate($user)->save();

        $warehouse->updatedBy()->associate($user)->save();

        return $user;
    }
}
