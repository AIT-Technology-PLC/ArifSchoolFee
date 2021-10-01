<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTenderReadingRequest;
use App\Models\Tender;

class TenderReadingController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function edit(Tender $tenderReading)
    {
        $tender = $tenderReading;

        $this->authorize('update', $tender);

        return view('tenders.readings.edit', compact('tender'));
    }

    public function update(UpdateTenderReadingRequest $request, Tender $tenderReading)
    {
        $tender = $tenderReading;

        $this->authorize('update', $tender);

        $tender->update($request->all());

        return redirect()->route('tenders.show', $tender->id);
    }
}
