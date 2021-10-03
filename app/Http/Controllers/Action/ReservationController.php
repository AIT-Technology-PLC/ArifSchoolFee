<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Gdn;
use App\Models\Reservation;
use App\Notifications\GdnPrepared;
use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationConverted;
use App\Notifications\ReservationMade;
use App\Services\InventoryOperationService;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    use NotifiableUsers, SubtractInventory, ApproveInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Reservation Management');

        $this->permission = 'Make Reservation';
    }

    public function reserve(Reservation $reservation)
    {
        $this->authorize('reserve', $reservation);

        if (!$reservation->isApproved()) {
            return back()->with('failedMessage', 'This reservation is not approved yet.');
        }

        $result = DB::transaction(function () use ($reservation) {
            $result = InventoryOperationService::reserve($reservation->reservationDetails);

            if (!$result['isReserved']) {
                DB::rollBack();

                return $result;
            }

            $reservation->reserve();

            Notification::send(
                $this->notifiableUsers('Approve Reservation', $reservation->createdBy),
                new ReservationMade($reservation)
            );

            return $result;
        });

        return $result['isReserved'] ? back() :
        back()->with('failedMessage', $result['unavailableProducts']);
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        if (!$reservation->isApproved()) {
            return back()->with('failedMessage', 'This reservation is not approved yet.');
        }

        if ($reservation->isConverted() && $reservation->reservable->isSubtracted()) {
            return back()->with('failedMessage', 'This reservation cannot be cancelled, it has been converted to DO.');
        }

        if (!$reservation->isConverted() && !$reservation->isReserved()) {
            $reservation->cancel();

            return back();
        }

        DB::transaction(function () use ($reservation) {
            if ($reservation->isConverted() && !$reservation->reservable->isSubtracted()) {
                $reservation->reservable()->forceDelete();
            }

            InventoryOperationService::cancelReservation($reservation->reservationDetails);

            $reservation->cancel();

            Notification::send(
                $this->notifiableUsers('Approve Reservation', $reservation->createdBy),
                new ReservationCancelled($reservation)
            );
        });

        return back();
    }

    public function convert(Reservation $reservation)
    {
        $this->authorize('convert', $reservation);

        $this->authorize('create', Gdn::class);

        if (!$reservation->isReserved()) {
            return back()->with('failedMessage', 'This reservation is not reserved yet.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            Notification::send(
                $this->notifiableUsers('Approve Reservation', $reservation->createdBy),
                new ReservationConverted($reservation)
            );

            $gdn = Gdn::create([
                'code' => Gdn::byBranch()->max('code') + 1,
                'customer_id' => $reservation->customer_id ?? null,
                'issued_on' => today(),
                'payment_type' => $reservation->payment_type,
                'description' => $reservation->description ?? '',
                'cash_received_in_percentage' => $reservation->cash_received_in_percentage,
            ]);

            $gdn->gdnDetails()->createMany($reservation->reservationDetails->toArray());

            $gdn->reservation()->save($reservation);

            Notification::send(
                $this->notifiableUsers('Approve GDN', $gdn->createdBy),
                new GdnPrepared($gdn)
            );
        });

        return back();
    }
}
