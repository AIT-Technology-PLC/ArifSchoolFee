<?php

namespace App\Traits;

trait VerifyModelIssuer
{
    public function isIssuedByMyBranch($user, $model)
    {
        return $user->hasRole('System Manager') || $user->warehouse_id == $model->warehouse_id;
    }
}
