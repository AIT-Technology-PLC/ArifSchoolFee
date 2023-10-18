<?php

namespace App\Http\Controllers\Resource;

use App\Actions\AutoBatchStoringAction;
use App\DataTables\SivDatatable;
use App\DataTables\SivDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSivRequest;
use App\Http\Requests\UpdateSivRequest;
use App\Models\Siv;
use App\Notifications\SivPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SivController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Siv Management');

        $this->authorizeResource(Siv::class, 'siv');
    }

    public function index(SivDatatable $datatable)
    {
        $datatable->builder()->setTableId('sivs-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalSivs = Siv::count();

        $totalApproved = Siv::notSubtracted()->approved()->count();

        $totalSubtracted = Siv::subtracted()->count();

        $totalNotApproved = Siv::notApproved()->count();

        return $datatable->render('sivs.index', compact('totalSivs', 'totalApproved', 'totalNotApproved', 'totalSubtracted'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('siv');

        $currentSivCode = nextReferenceNumber('sivs');

        return view('sivs.create', compact('warehouses', 'currentSivCode'));
    }

    public function store(StoreSivRequest $request)
    {
        $siv = DB::transaction(function () use ($request) {
            $siv = Siv::create($request->safe()->except('siv'));

            $siv->sivDetails()->createMany($request->validated('siv'));

            AutoBatchStoringAction::execute($siv, $request->validated('siv'), 'sivDetails');

            $siv->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }

    public function show(Siv $siv, SivDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('siv-details-datatable');

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'customFieldValues.customField']);

        return $datatable->render('sivs.show', compact('siv'));
    }

    public function edit(Siv $siv)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse']);

        $warehouses = authUser()->getAllowedWarehouses('siv');

        return view('sivs.edit', compact('siv', 'warehouses'));
    }

    public function update(UpdateSivRequest $request, Siv $siv)
    {
        if ($siv->isApproved()) {
            $siv->update($request->only('description'));

            return redirect()->route('sivs.show', $siv->id);
        }

        DB::transaction(function () use ($request, $siv) {
            $siv->update($request->safe()->except('siv'));

            $siv->sivDetails()->forceDelete();

            $siv->sivDetails()->createMany($request->validated('siv'));

            AutoBatchStoringAction::execute($siv, $request->validated('siv'), 'sivDetails');

            $siv->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('sivs.show', $siv->id);
    }

    public function destroy(Siv $siv)
    {
        abort_if(
            $siv->isApproved() && authUser()->cannot('Delete Approved SIV'),
            403
        );

        $siv->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
