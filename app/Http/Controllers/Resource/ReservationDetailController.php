<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ReservationDetail;

class ReservationDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Reservation Management');
    }

    public function destroy(ReservationDetail $reservationDetail)
    {
        $this->authorize('delete', $reservationDetail->reservation);

        if (($reservationDetail->reservation->isConverted() || $reservationDetail->reservation->isReserved()) &&
            !$reservationDetail->reservation->isCancelled()) {
            abort(403);
        }

        if (($reservationDetail->reservation->isApproved() || $reservationDetail->reservation->isCancelled()) &&
            !auth()->user()->can('Delete Approved Reservation')) {
            abort(403);
        }

        $reservationDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
