<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomFieldRequest;
use App\Http\Requests\UpdateCustomFieldRequest;
use App\Models\CustomField;

class CustomFieldController extends Controller
{
    public function __construct(PadService $padService)
    {
        $this->middleware('isFeatureAccessible:Custom Field Management');

        $this->authorizeResource(CustomField::class);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreCustomFieldRequest $request)
    {
        //
    }

    public function show(CustomField $customField)
    {
        //
    }

    public function edit(CustomField $customField)
    {
        //
    }

    public function update(UpdateCustomFieldRequest $request, CustomField $customField)
    {
        //
    }

    public function destroy(CustomField $customField)
    {
        //
    }
}
