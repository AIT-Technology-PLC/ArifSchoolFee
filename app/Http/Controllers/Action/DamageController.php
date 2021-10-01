<?php

namespace App\Http\Controllers\Action;

use App\Traits\NotifiableUsers;
use App\Traits\ApproveInventory;
use App\Traits\SubtractInventory;
use App\Http\Controllers\Controller;

class DamageController extends Controller
{
    use NotifiableUsers, SubtractInventory, ApproveInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');

        $this->permission = 'Subtract Damage';
    }
}
