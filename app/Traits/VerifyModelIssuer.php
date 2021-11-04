<?php

namespace App\Traits;

use App\Models\Company;

trait VerifyModelIssuer
{
    public function isIssuedByMyCompany($user, $model, $verifyIssuerBranch = false)
    {
        if ($model instanceof Company) {
            return $user->employee->company_id == $model->id;
        }

        return $verifyIssuerBranch ?
        $user->employee->company_id == $model->company_id && $this->isIssuedByMyBranch($user, $model) :
        $user->employee->company_id == $model->company_id;
    }

    public function isIssuedByMyBranch($user, $model)
    {
        return auth()->user()->hasRole('System Manager') || $user->warehouse_id == $model->warehouse_id;
    }
}
