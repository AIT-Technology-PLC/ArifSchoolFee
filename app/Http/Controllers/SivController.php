<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSivRequest;
use App\Models\Product;
use App\Models\Siv;
use App\Models\Warehouse;
use App\Notifications\SivPrepared;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Http\Request;
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
        return view('sivs.index');
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

    public function edit(Siv $siv)
    {
        return view('sivs.edit');
    }

    public function update(Request $request, Siv $siv)
    {
        //
    }

    public function destroy(Siv $siv)
    {
        //
    }
}
