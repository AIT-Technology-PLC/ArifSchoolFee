<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Customer;
use App\Models\Reservation;
use App\Notifications\ReservationPrepared;
use App\Services\ReservationService;
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
        $reservations = Reservation::with(['reservationDetails', 'createdBy', 'updatedBy', 'approvedBy', 'customer', 'reservable'])
            ->latest()->get();

        $totalReservations = Reservation::count();

        $totalConverted = Reservation::whereNotNull('converted_by')
            ->whereNull('cancelled_by')->count();

        $totalReserved = Reservation::whereNotNull('reserved_by')->whereNull('converted_by')
            ->whereNull('cancelled_by')->count();

        $totalNotApproved = Reservation::whereNull('approved_by')
            ->whereNull('cancelled_by')->count();

        $totalApproved = Reservation::whereNotNull('approved_by')->whereNull('reserved_by')->whereNull('converted_by')
            ->whereNull('cancelled_by')->count();

        $totalCancelled = Reservation::whereNotNull('cancelled_by')->count();

        return view('reservations.index',
            compact('reservations', 'totalReservations', 'totalConverted', 'totalReserved', 'totalCancelled', 'totalNotApproved', 'totalApproved'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $warehouses = auth()->user()->getAllowedWarehouses('sales');

        $currentReservationCode = Reservation::byBranch()->max('code') + 1;

        return view('reservations.create', compact('customers', 'warehouses', 'currentReservationCode'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = DB::transaction(function () use ($request) {
            $reservation = Reservation::create($request->except('reservation'));

            $reservation->reservationDetails()->createMany($request->reservation);

            Notification::send(notifiables('Approve Reservation'), new ReservationPrepared($reservation));

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
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $warehouses = auth()->user()->getAllowedWarehouses('sales');

        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse']);

        return view('reservations.edit', compact('reservation', 'customers', 'warehouses'));
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

        Notification::send(notifiables('Approve Reservation'), new ReservationPrepared($reservation));

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function destroy(Reservation $reservation)
    {
        if (($reservation->isConverted() || $reservation->isReserved()) && !$reservation->isCancelled()) {
            abort(403);
        }

        if (($reservation->isApproved() || $reservation->isCancelled()) && !auth()->user()->can('Delete Approved Reservation')) {
            abort(403);
        }

        $reservation->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
