<?php

namespace App\Policies;

use App\Models\Company;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function update(User $user, Company $company)
    {
        return $this->doesModelBelongToMyCompany($user, $company->id) && $user->can('Update Company');
    }
}
