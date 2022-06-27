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

        abort_if(($reservationDetail->reservation->isConverted() || $reservationDetail->reservation->isReserved()) &&
            !$reservationDetail->reservation->isCancelled(), 403);

        abort_if(($reservationDetail->reservation->isApproved() || $reservationDetail->reservation->isCancelled()) &&
            !authUser()->can('Delete Approved Reservation'), 403);

        $reservationDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
