<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ContactDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Contact Management');

        $this->authorizeResource(Contact::class, 'contact');
    }

    public function index(ContactDatatable $datatable)
    {
        $datatable->builder()->setTableId('contacts-datatable')->orderBy(1, 'asc');

        $totalContacts = Contact::count();

        return $datatable->render('contacts.index', compact('totalContacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(StoreContactRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('Contact') as $contact) {
                Contact::create($contact);
            }
        });

        return redirect()->route('contacts.index')->with('successMessage', 'New Contact are added.');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());

        return redirect()->route('contacts.index');
    }

    public function destroy(Contact $contact)
    {
        $contact->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
