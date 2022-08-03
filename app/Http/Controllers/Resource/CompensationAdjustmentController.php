<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompensationAdjustment;
use App\Http\Requests\StoreCompensationAdjustmentRequest;
use App\Http\Requests\UpdateCompensationAdjustmentRequest;

class CompensationAdjustmentController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreCompensationAdjustmentRequest $request ,CompensationAdjustment $compensationAdjustment)
    {
        //
    }

    public function show(CompensationAdjustment $compensationAdjustment)
    {
        //
    }

    public function edit(CompensationAdjustment $compensationAdjustment)
    {
        //
    }

    public function update(UpdateCompensationAdjustmentRequest $request, CompensationAdjustment $compensationAdjustment)
    {
        //
    }

    public function destroy(CompensationAdjustment $compensationAdjustment)
    {
        //
    }
}