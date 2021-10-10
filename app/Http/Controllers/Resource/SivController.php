<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSivRequest;
use App\Http\Requests\UpdateSivRequest;
use App\Models\Customer;
use App\Models\Siv;
use App\Notifications\SivPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SivController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Siv Management');

        $this->authorizeResource(Siv::class, 'siv');
    }

    public function index()
    {
        $sivs = Siv::with(['createdBy', 'updatedBy', 'approvedBy'])->latest()->get();

        $totalSivs = Siv::count();

        $totalApproved = Siv::whereNotNull('approved_by')->count();

        $totalNotApproved = Siv::whereNull('approved_by')->count();

        return view('sivs.index', compact('sivs', 'totalSivs', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('siv');

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $currentSivCode = Siv::byBranch()->max('code') + 1;

        return view('sivs.create', compact('warehouses', 'customers', 'currentSivCode'));
    }

    public function store(StoreSivRequest $request)
    {
        $siv = DB::transaction(function () use ($request) {
            $siv = Siv::create($request->except('siv'));

            $siv->sivDetails()->createMany($request->siv);

            Notification::send(notifiables('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }

    public function show(Siv $siv)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse']);

        return view('sivs.show', compact('siv'));
    }

    public function edit(Siv $siv)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse']);

        $warehouses = auth()->user()->getAllowedWarehouses('siv');

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        return view('sivs.edit', compact('siv', 'warehouses', 'customers'));
    }

    public function update(UpdateSivRequest $request, Siv $siv)
    {
        if ($siv->isApproved()) {
            $siv->update($request->only('description'));

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

        return back()->with('deleted', 'Deleted successfully.');
    }
}
