<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\SivDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSivRequest;
use App\Http\Requests\UpdateSivRequest;
use App\Models\Siv;
use App\Notifications\SivPrepared;
use App\Services\NextReferenceNumService;
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

        $totalApproved = Siv::approved()->count();

        $totalNotApproved = Siv::notApproved()->count();

        return $datatable->render('sivs.index', compact('totalSivs', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('siv');

        $currentSivCode = NextReferenceNumService::table('sivs');

        return view('sivs.create', compact('warehouses', 'currentSivCode'));
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

        return view('sivs.edit', compact('siv', 'warehouses'));
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
        abort_if(
            $siv->isApproved() && auth()->user()->cannot('Delete Approved SIV'),
            403
        );

        $siv->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
