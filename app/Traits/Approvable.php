<?php

namespace App\Traits;

trait Approvable
{
    public function approvedBy()
    {
        $isApproved = request()->validate([
            'is_approved' => 'sometimes|required|integer',
        ]);

        return $isApproved['is_approved'] ? auth()->user()->id : '';
    }
}
