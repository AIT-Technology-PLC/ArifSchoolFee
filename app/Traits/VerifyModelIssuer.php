<?php

namespace App\Traits;

use App\Models\Company;

trait VerifyModelIssuer
{
    public function isIssuedByMyCompany($user, $model)
    {
        if ($model instanceof Company) {
            return $user->employee->company_id == $model->id;
        }

        return $user->employee->company_id == $model->company_id;
    }

    public function isIssuedByMyBranch($user, $model)
    {
        return $user->warehouse_id == $model->warehouse_id;
    }
}
