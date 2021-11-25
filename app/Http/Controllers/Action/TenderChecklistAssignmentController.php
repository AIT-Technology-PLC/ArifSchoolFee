<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTenderChecklistAssignmentRequest;
use App\Models\Employee;
use App\Models\Tender;

class TenderChecklistAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function edit(Tender $tender)
    {
        $this->authorize('assign', $tender);

        $tender->load('tenderChecklists.generalTenderChecklist');

        $users = Employee::enabled()
            ->whereHas('user', function ($query) {
                return $query->permission(['Read Tender', 'Update Tender']);
            })
            ->get()
            ->pluck('user');

        return view('tender-checklist-assignments.edit', compact('tender', 'users'));
    }

    public function update(UpdateTenderChecklistAssignmentRequest $request, Tender $tender)
    {
        $this->authorize('assign', $tender);

        foreach ($request->checklist as $checklistAssignment) {
            $tender->tenderChecklists()
                ->where('id', $checklistAssignment['id'])
                ->update([
                    'assigned_to' => $checklistAssignment['assigned_to'] ?? null,
                ]);
        }

        return redirect()->route('tenders.show', $tender->id)
            ->with('checklistAssigned', 'Assignments were updated successfully.');
    }
}
