<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AdvancementDatatable;
use App\DataTables\AdvancementDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvancementRequest;
use App\Http\Requests\UpdateAdvancementRequest;
use App\Models\Advancement;
use App\Models\User;
use App\Notifications\AdvancementCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdvancementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Advancement Management');

        $this->authorizeResource(Advancement::class);
    }

    public function index(AdvancementDatatable $datatable)
    {
        $datatable->builder()->setTableId('advancements-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalAdvancements = Advancement::count();

        $totalApproved = Advancement::approved()->count();

        $totalNotApproved = Advancement::notApproved()->count();

        return $datatable->render('advancements.index', compact('totalAdvancements', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $users = User::whereIn('warehouse_id', authUser()->getAllowedWarehouses('hr')->pluck('id'))->with('employee')->orderBy('name')->get();

        return view('advancements.create', compact('users'));
    }

    public function store(StoreAdvancementRequest $request)
    {
        $advancement = DB::transaction(function () use ($request) {
            $advancement = Advancement::create($request->safe()->except('advancement'));

            $advancement->advancementDetails()->createMany($request->validated('advancement'));

            Notification::send(Notifiables::byNextActionPermission('Approve Advancement'), new AdvancementCreated($advancement));

            return $advancement;
        });

        return redirect()->route('advancements.show', $advancement->id);
    }

    public function show(Advancement $advancement, AdvancementDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('advancement-details-datatable');

        return $datatable->render('advancements.show', compact('advancement'));
    }

    public function edit(Advancement $advancement)
    {
        if ($advancement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an advancement that is approved.');
        }

        $users = User::whereIn('warehouse_id', authUser()->getAllowedWarehouses('hr')->pluck('id'))->with('employee')->orderBy('name')->get();

        $advancement->load(['advancementDetails']);

        return view('advancements.edit', compact('advancement', 'users'));
    }

    public function update(UpdateAdvancementRequest $request, Advancement $advancement)
    {
        if ($advancement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an advancement that is approved.');
        }

        DB::transaction(function () use ($request, $advancement) {
            $advancement->update($request->safe()->except('advancement'));

            $advancement->advancementDetails()->forceDelete();

            $advancement->advancementDetails()->createMany($request->validated('advancement'));

        });

        return redirect()->route('advancements.show', $advancement->id);
    }

    public function destroy(Advancement $advancement)
    {
        if ($advancement->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an advancement that is approved.');
        }

        $advancement->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
