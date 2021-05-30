<?php

namespace App\Http\Controllers;

use App\Models\Models\Siv;
use App\Traits\ApproveInventory;
use Illuminate\Http\Request;

class SivController extends Controller
{
    use ApproveInventory;

    private $siv, $permission;

    public function __construct(Siv $siv)
    {
        // $this->authorizeResource(Siv::class, 'siv');

        $this->siv = $siv;

        $this->permission = 'Execute SIV';
    }

    public function index()
    {
        return view('sivs.index');
    }

    public function create()
    {
        return view('sivs.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Siv $siv)
    {
        return view('sivs.show');
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
