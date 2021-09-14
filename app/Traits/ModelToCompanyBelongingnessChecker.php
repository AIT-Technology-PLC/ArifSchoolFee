<?php

namespace App\Traits;

trait ModelToCompanyBelongingnessChecker
{
    public function doesModelBelongToMyCompany($user, $model)
    {
        return $user->employee->company_id == $model->company_id;
    }
}
