<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Http\Requests\UpdateTenderReadingRequest;

class TenderReadingController extends Controller
{
    public function __construct(Tender $tender)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Tender Management');

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
