<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AcademicYearDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Academic Year');

        $this->authorizeResource(AcademicYear::class);
    }

    public function index(AcademicYearDatatable $datatable)
    {
        $datatable->builder()->setTableId('academic-years-datatable')->orderBy(0, 'asc');

        $totalAcademicYears= AcademicYear::count();

        return $datatable->render('academic-years.index', compact('totalAcademicYears'));
    }

    public function create()
    {
        return view('academic-years.create');
    }

    public function store(StoreAcademicYearRequest $request)
    {
        DB::transaction(function () use ($request) {
            AcademicYear::firstOrCreate(
                $request->safe()->only(['title', 'year'] + ['company_id' => userCompany()->id]),
                array_merge(
                    $request->safe()->except(['title', 'year'] + ['company_id' => userCompany()->id]),
                    [
                        'starting_period' => Carbon::createFromDate($request->year, 1, 1),
                        'ending_period' => Carbon::createFromDate($request->year, 12, 31),
                    ]
                )
            );
        });

        return redirect()->route('academic-years.index')->with('successMessage', 'New Academic Year Created Successfully.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        if ($academicYear->students()->exists()) {
            return back()->with(['failedMessage' => 'This Academic Year data is being used and cannot be edited.']);
        }

        DB::transaction(function () use ($request, $academicYear) {
            $dataToUpdate = array_merge(
                $request->safe()->only(['title', 'year']),
                ['company_id' => userCompany()->id],
                [
                    'starting_period' => Carbon::createFromDate($request->year, 1, 1),
                    'ending_period' => Carbon::createFromDate($request->year, 12, 31),
                ]
            );
    
            $academicYear->update($dataToUpdate);
        });
        
        return redirect()->route('academic-years.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->students()->exists()) {
            return back()->with(['failedMessage' => 'This Academic Year data is being used and cannot be deleted.']);
        }

        $academicYear->forceDelete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}
