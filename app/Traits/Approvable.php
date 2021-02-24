<?php

namespace App\Traits;

trait Approvable
{
    public function approvedBy()
    {
        $isApproved = request()->validate([
            'is_approved' => 'sometimes|required|integer',
        ]);

        if (count($isApproved)) {
            return $isApproved['is_approved'] ? auth()->user()->id : null;
        }

        return null;

    }
}
