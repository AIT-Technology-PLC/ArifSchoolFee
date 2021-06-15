<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSivRequest;
use App\Http\Requests\UpdateSivRequest;
use App\Models\Product;
use App\Models\Siv;
use App\Models\Warehouse;
use App\Notifications\SivExecuted;
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
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Siv');

        $this->authorizeResource(Siv::class, 'siv');

        $this->permission = 'Execute SIV';
    }

    public function index()
    {
        $sivs = Siv::companySiv()->with(['createdBy', 'updatedBy', 'approvedBy', 'executedBy'])->latest()->get();

        $totalSivs = $sivs->count();

        $totalNotApproved = $sivs->whereNull('approved_by')->count();

        $totalNotExecuted = $sivs->whereNotNull('approved_by')->whereNull('executed_by')->count();

        return view('sivs.index', compact('sivs', 'totalSivs', 'totalNotApproved', 'totalNotExecuted'));
    }

    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

        return view('sivs.create', compact('products', 'warehouses', 'currentSivCode'));
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

    public function edit(Siv $siv, Product $product, Warehouse $warehouse)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse']);

        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('sivs.edit', compact('siv', 'products', 'warehouses'));
    }

    public function update(UpdateSivRequest $request, Siv $siv)
    {
        if ($siv->isApproved()) {
            $siv->update($request->only('desciption', 'updated_by'));

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
            return view('errors.permission_denied');
        }

        $siv->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function execute(Siv $siv)
    {
        $this->authorize('execute', $siv);

        if (!$siv->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This SIV is not approved');
        }

        if ($siv->isExecuted()) {
            return redirect()->back()->with('failedMessage', 'This SIV is already executed');
        }

        DB::transaction(function () use ($siv) {
            $siv->execute();

            Notification::send(
                $this->notifiableUsers('Approve SIV', $siv->createdBy),
                new SivExecuted($siv)
            );
        });

        return redirect()->back();
    }

    public function printed(Siv $siv)
    {
        $this->authorize('view', $siv);

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'company', 'createdBy', 'approvedBy']);

        return view('sivs.print', compact('siv'));
    }
}
