<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTenderReadingRequest;
use App\Models\Tender;

class TenderReadingController extends Controller
{
    public function __construct(Tender $tender)
    {
        $this->middleware('isFeatureAcccessible:Tender Management');

        $this->authorizeResource(Tender::class, 'tender');

        $this->tender = $tender;
    }

    public function edit(Tender $tender)
    {
        return view('tenders.readings.edit', compact('tender'));
    }

    public function update(UpdateTenderReadingRequest $request, Tender $tender)
    {
        $tender->update($request->all());

        return redirect()->route('tenders.show', $tender->id);
    }
}
