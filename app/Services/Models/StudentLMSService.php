<?php

namespace App\Services\Models;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentLMSService
{
    public static function sendPayload($student)
    {
        // Prepare LMS payload for LMS (Arif LMS - Learning Management System)
        $lmsPayload = [
            'name' => $student->first_name . ' ' . $student->father_name,
            'email' => $student->phone,
            'school' => $student->company->name,
            'password' => $student->first_name . now()->year,
            'address' => $student->current_address ?? null,
            'gender' => $student->gender ?? null,
        ];

        // Attempt to send the request with retries
        $response = Http::retry(3, 100)
            ->post('https://lms.arifeducation.com/api/v1/school-students', $lmsPayload);

        // Handle LMS API response (optional, can log errors or success message)
        if ($response->successful()) {
            Log::info('Student registered in LMS successfully.', ['student_id' => $student->id]);
        } else {
            Log::error('Failed to register student in LMS.', ['student_id' => $student->id, 'response' => $response->body()]);
        }
    }
}