<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function getClassesBySchool($schoolId)
    { 
        $classes = SchoolClass::where('company_id', $schoolId)->get();

        if ($classes->isEmpty()) {
            return response()->json(['error' => 'Class data not found'], 500);
        }

        return response()->json($classes);
    }
}
