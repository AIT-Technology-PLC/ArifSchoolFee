<?php

namespace App\Http\Controllers\Action;

use App\Actions\ConvertToSivAction;
use App\Http\Controllers\Controller;
use App\Models\Gdn;
use App\Models\Siv;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;

class GdnController extends Controller
{
    use NotifiableUsers, SubtractInventory, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->permission = 'Subtract GDN';
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('gdns.print', compact('gdn'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }

    public function convertToSiv(Gdn $gdn, ConvertToSivAction $action)
    {
        $this->authorize('view', $gdn);

        $this->authorize('create', Siv::class);

        $siv = $action->execute(
            'DO',
            $gdn->code,
            $gdn->customer->company_name ?? '',
            $gdn->approved_by,
            $gdn->gdnDetails()->get(['product_id', 'warehouse_id', 'quantity'])->toArray(),
        );

        return redirect()->route('sivs.show', $siv->id);
    }
}
