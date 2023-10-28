<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CustomFieldDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomFieldRequest;
use App\Http\Requests\UpdateCustomFieldRequest;
use App\Models\CustomField;
use Illuminate\Support\Facades\DB;

class CustomFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Custom Field Management');

        $this->authorizeResource(CustomField::class);
    }

    public function index(CustomFieldDatatable $datatable)
    {
        return $datatable->render('custom-fields.index');
    }

    public function create()
    {
        if (limitReached('custom-field', CustomField::active()->count())) {
            return back()->with('failedMessage', __('messages.limit_reached', ['limit' => 'custom fields']));
        }

        return view('custom-fields.create');
    }

    public function store(StoreCustomFieldRequest $request)
    {
        if (limitReached('custom-field', CustomField::active()->count())) {
            return back()->with('failedMessage', __('messages.limit_reached', ['limit' => 'custom fields']));
        }

        DB::transaction(function () use ($request) {
            foreach ($request->validated('customField') as $customField) {
                CustomField::create($customField);
            }
        });

        return redirect()->route('custom-fields.index')->with('successMessage', 'New custom field(s) are added.');
    }

    public function edit(CustomField $customField)
    {
        return view('custom-fields.edit', compact('customField'));
    }

    public function update(UpdateCustomFieldRequest $request, CustomField $customField)
    {
        if (!$customField->isActive() && $request->validated('is_active') && limitReached('custom-field', CustomField::active()->count())) {
            $customField->update($request->safe()->except('is_active'));

            return redirect()->route('custom-fields.index')->with('failedMessage', __('messages.limit_reached', ['limit' => 'custom fields']));
        }

        $customField->update($request->validated());

        return redirect()->route('custom-fields.index');
    }

    public function destroy(CustomField $customField)
    {
        if ($customField->isActive()) {
            return back()->with('failedMessage', 'This field is currently in use. To delete the field, first disable it.');
        }

        $customField->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
