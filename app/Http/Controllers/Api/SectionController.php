<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;

class SectionController extends Controller
{
    public function getSectionsByClass($classId)
    {    
        $schoolClass = SchoolClass::find($classId);

        if ($schoolClass) {
            $sections = $schoolClass->sections;
            return response()->json($sections);
        }

        return response()->json(['error' => 'Section data not found'], 404);
    }
}
