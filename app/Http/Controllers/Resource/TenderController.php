<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderRequest;
use App\Http\Requests\UpdateTenderRequest;
use App\Models\Customer;
use App\Models\Tender;
use App\Models\TenderStatus;
use App\Notifications\TenderStatusChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(Tender::class, 'tender');
    }

    public function index()
    {
        $tenders = (new Tender)
            ->getAll()
            ->loadCount('tenderDetails')
            ->load(['customer', 'tenderChecklists', 'createdBy', 'updatedBy']);

        $totalTenders = Tender::count();

        return view('tenders.index', compact('tenders', 'totalTenders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();

        $tenderStatuses = TenderStatus::orderBy('status')->get();

        return view('tenders.create', compact('customers', 'tenderStatuses'));
    }

    public function store(StoreTenderRequest $request)
    {
        $tender = DB::transaction(function () use ($request) {
            $tender = Tender::create($request->except('tender'));

            $tender->tenderDetails()->createMany($request->tender);

            return $tender;
        });

        return redirect()->route('tenders.show', $tender);
    }

    public function show(Tender $tender)
    {
        $tender->load(['customer', 'tenderDetails.product', 'tenderChecklists.generalTenderChecklist.tenderChecklistType']);

        return view('tenders.show', compact('tender'));
    }

    public function edit(Tender $tender)
    {
        $tender->load(['tenderDetails.product']);

        $customers = Customer::orderBy('company_name')->get();

        $tenderStatuses = TenderStatus::orderBy('status')->get();

        return view('tenders.edit', compact('tender', 'customers', 'tenderStatuses'));
    }

    public function update(UpdateTenderRequest $request, Tender $tender)
    {
        DB::transaction(function () use ($request, $tender) {
            $originalStatus = $tender->status;

            $tender->update($request->except('tender'));

            for ($i = 0; $i < count($request->tender); $i++) {
                $tender->tenderDetails[$i]->update($request->tender[$i]);
            }

            if ($tender->wasChanged('status')) {
                Notification::send(notifiables('Read Tender'), new TenderStatusChanged($originalStatus, $tender));
            }
        });

        return redirect()->route('tenders.show', $tender->id);
    }

    public function destroy(Tender $tender)
    {
        $tender->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
