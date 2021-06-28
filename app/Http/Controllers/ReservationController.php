<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Warehouse;
use App\Notifications\ReservationMade;
use App\Notifications\ReservationPrepared;
use App\Services\InventoryOperationService;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    use NotifiableUsers, SubtractInventory;

    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Reservation Management');

        $this->authorizeResource(Reservation::class, 'reservation');

        $this->permission = 'Make Reservation';
    }

    public function index()
    {
        $reservations = Reservation::companyReservation()
            ->with(['reservationDetails', 'createdBy', 'updatedBy', 'approvedBy', 'company', 'customer'])
            ->latest()->get();

        $totalReservations = $reservations->count();

        $totalConverted = $reservations->whereNotNull('converted_by')->count();

        $totalReserved = $reservations->whereNotNull('reserved_By')->whereNull('converted_by')->count();

        $totalCancelled = $reservations->whereNotNull('cancelled_By')->count();

        $totalNotApproved = $reservations->whereNull('approved_By')->count();

        return view('reservations.index', compact('reservations', 'totalReservations', 'totalConverted', 'totalReserved', 'totalCancelled', 'totalNotApproved'));
    }

    public function create(Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentReservationCode = (Reservation::select('code')->companyReservation()->latest()->first()->code) ?? 0;

        return view('reservations.create', compact('products', 'customers', 'warehouses', 'currentReservationCode'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = DB::transaction(function () use ($request) {
            $reservation = Reservation::create($request->except('reservation'));

            $reservation->reservationDetails()->createMany($request->reservation);

            Notification::send($this->notifiableUsers('Approve Reservation'), new ReservationPrepared($reservation));

            return $reservation;
        });

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse', 'customer', 'company']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation, Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse']);

        return view('reservations.edit', compact('reservation', 'products', 'customers', 'warehouses'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        if ($reservation->isCancelled() || $reservation->isConverted()) {
            return redirect()->route('reservations.show', $reservation->id);
        }

        DB::transaction(function () use ($request, $reservation) {
            $reservation->update($request->except('reservation'));

            for ($i = 0; $i < count($request->reservation); $i++) {
                $reservation->reservationDetails[$i]->update($request->reservation[$i]);
            }
        });

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->isConverted() || $reservation->isCancelled()) {
            return view('errors.permission_denied');
        }

        if ($reservation->isApproved() && !auth()->user()->can('Delete Approved Reservation')) {
            return view('errors.permission_denied');
        }

        $reservation->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function reserve(Reservation $reservation)
    {
        $this->authorize('reserve', $reservation);

        if (!$reservation->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This reservation is not approved yet');
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

        return $result['isReserved'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }
}
