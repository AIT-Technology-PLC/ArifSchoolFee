<?php

namespace App\Policies;

use App\Models\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Company $company)
    {
        $doesAdminBelongsToCompany = $user->employee->company_id == $company->id;

        return $doesAdminBelongsToCompany && $user->can('Update Employee');
    }

    public function onlyPremiumOrProfessional(User $user)
    {
        return $user->employee->company->isCompanyPremiumOrProfessionalMember();
    }

    public function onlyPremium(User $user)
    {
        return $user->employee->company->isCompanyPremiumMember();
    }
}
