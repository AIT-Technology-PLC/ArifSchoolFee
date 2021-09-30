<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Traits\AddInventory;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;

class GrnController extends Controller
{
    use NotifiableUsers, AddInventory, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');

        $this->permission = 'Add GRN';
    }
}
