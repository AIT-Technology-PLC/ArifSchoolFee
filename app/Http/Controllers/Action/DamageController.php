<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Traits\ApproveInventory;
use App\Traits\SubtractInventory;

class DamageController extends Controller
{
    use SubtractInventory, ApproveInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');

        $this->permission = 'Subtract Damage';
    }
}
