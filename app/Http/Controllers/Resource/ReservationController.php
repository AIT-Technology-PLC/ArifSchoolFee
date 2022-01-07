<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Notifications\ReservationPrepared;
use App\Services\Models\ReservationService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->middleware('isFeatureAccessible:Reservation Management');

        $this->authorizeResource(Reservation::class, 'reservation');

        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $reservations = Reservation::query()
            ->with(['reservationDetails', 'createdBy', 'updatedBy', 'approvedBy', 'customer', 'reservable'])
            ->latest('code')
            ->get();

        $totalReservations = Reservation::count();

        $totalConverted = Reservation::converted()->notCancelled()->count();

        $totalReserved = Reservation::reserved()->notConverted()->notCancelled()->count();

        $totalNotApproved = Reservation::notApproved()->notCancelled()->count();

        $totalApproved = Reservation::approved()->notReserved()->notConverted()->notCancelled()->count();

        $totalCancelled = Reservation::cancelled()->count();

        return view('reservations.index',
            compact('reservations', 'totalReservations', 'totalConverted', 'totalReserved', 'totalCancelled', 'totalNotApproved', 'totalApproved'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('sales');

        $currentReservationCode = nextReferenceNumber('reservations');

        return view('reservations.create', compact('warehouses', 'currentReservationCode'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = DB::transaction(function () use ($request) {
            $reservation = Reservation::create($request->except('reservation'));

            $reservation->reservationDetails()->createMany($request->reservation);

            Notification::send(Notifiables::byNextActionPermission('Approve Reservation'), new ReservationPrepared($reservation));

            return $reservation;
        });

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse', 'customer']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $warehouses = auth()->user()->getAllowedWarehouses('sales');

        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse']);

        return view('reservations.edit', compact('reservation', 'warehouses'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        [$isExecuted, $message] = $this->reservationService->update(
            $reservation, $request->except('reservation'), $request->reservation
        );

        if (!$isExecuted) {
            return redirect()->route('reservations.show', $reservation->id)
                ->with('failedMessage', $message);
        }

        if ($reservation->wasChanged('approved_by')) {
            Notification::send(Notifiables::byNextActionPermission('Approve Reservation'), new ReservationPrepared($reservation));
        }

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function destroy(Reservation $reservation)
    {
        abort_if(($reservation->isConverted() || $reservation->isReserved()) && !$reservation->isCancelled(), 403);

        abort_if(($reservation->isApproved() || $reservation->isCancelled()) && !auth()->user()->can('Delete Approved Reservation'), 403);

        $reservation->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
