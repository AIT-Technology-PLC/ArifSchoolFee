<?php

namespace App\Traits;

trait ModelToCompanyBelongingnessChecker
{
    public function doesModelBelongToMyCompany($user, $model)
    {
        if (is_numeric($model)) {
            return $user->employee->company_id == $model;
        }

        return $user->employee->company_id == $model->company_id;
    }
}
