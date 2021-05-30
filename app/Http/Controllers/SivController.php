<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSivRequest;
use App\Http\Requests\UpdateSivRequest;
use App\Models\Product;
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

    private $siv, $permission;

    public function __construct(Siv $siv)
    {
        $this->authorizeResource(Siv::class, 'siv');

        $this->siv = $siv;

        $this->permission = 'Execute SIV';
    }

    public function index()
    {
        $sivs = Siv::companySiv()->with(['createdBy', 'updatedBy', 'approvedBy', 'executedBy'])->get();

        $totalSivs = Siv::companySiv()->count();

        $totalNotApproved = SIV::companySiv()->whereNull('approved_by')->count();

        $totalNotExecuted = SIV::companySiv()->whereNotNull('approved_by')->whereNull('executed_by')->count();

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
            $siv = $this->siv->create($request->except('siv'));

            $siv->sivDetails()->createMany($request->siv);

            Notification::send($this->notifiableUsers('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }

    public function show(Siv $siv)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'company']);

        return view('sivs.show', compact('siv'));
    }

    public function edit(Siv $siv, Product $product, Warehouse $warehouse)
    {

        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('sivs.edit', compact('siv', 'products', 'warehouses'));
    }

    public function update(UpdateSivRequest $request, Siv $siv)
    {
        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'company']);

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
        //
    }
}
