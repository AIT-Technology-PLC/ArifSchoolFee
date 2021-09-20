<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSivRequest;
use App\Http\Requests\UpdateSivRequest;
use App\Models\Customer;
use App\Models\Siv;
use App\Models\Warehouse;
use App\Notifications\SivPrepared;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SivController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $permission;

    public function __construct(Siv $siv)
    {
        $this->middleware('isFeatureAccessible:Siv Management');

        $this->authorizeResource(Siv::class, 'siv');
    }

    public function index()
    {
        $sivs = (new Siv())->getAll()->load(['createdBy', 'updatedBy', 'approvedBy']);

        $totalSivs = $sivs->count();

        $totalApproved = $sivs->whereNotNull('approved_by')->count();

        $totalNotApproved = $sivs->whereNull('approved_by')->count();

        return view('sivs.index', compact('sivs', 'totalSivs', 'totalApproved', 'totalNotApproved'));
    }

    public function create(Warehouse $warehouse, Customer $customer)
    {
        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->assignedWarehouse());

        $customers = $customer->getCustomerNames();

        $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

        return view('sivs.create', compact('warehouses', 'customers', 'currentSivCode'));
    }

    public function store(StoreSivRequest $request)
    {
        $siv = DB::transaction(function () use ($request) {
            $siv = Siv::create($request->except('siv'));

            $siv->sivDetails()->createMany($request->siv);

            Notification::send($this->notifiableUsers('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }

    public function show(Siv $siv)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse']);

        return view('sivs.show', compact('siv'));
    }

    public function edit(Siv $siv, Warehouse $warehouse, Customer $customer)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse']);

        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->assignedWarehouse());

        $customers = $customer->getCustomerNames();

        return view('sivs.edit', compact('siv', 'warehouses', 'customers'));
    }

    public function update(UpdateSivRequest $request, Siv $siv)
    {
        if ($siv->isApproved()) {
            $siv->update($request->only('description', 'updated_by'));

            return redirect()->route('sivs.show', $siv->id);
        }

        DB::transaction(function () use ($request, $siv) {
            $siv->update($request->except('siv'));

            for ($i = 0; $i < count($request->siv); $i++) {
                $siv->sivDetails[$i]->update($request->siv[$i]);
            }
        });

        return redirect()->route('sivs.show', $siv->id);
    }

    public function destroy(Siv $siv)
    {
        if ($siv->isApproved() && !auth()->user()->can('Delete Approved SIV')) {
            abort(403);
        }

        $siv->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
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
