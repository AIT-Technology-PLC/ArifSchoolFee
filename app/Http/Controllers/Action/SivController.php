<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;

class SivController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Siv Management');

        $this->permission = 'Approve SIV';
    }

    public function printed(Siv $siv)
    {
        $this->authorize('view', $siv);

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('sivs.print', compact('siv'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
