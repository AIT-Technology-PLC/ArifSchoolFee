<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Warehouse;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Http\Request;

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

        $totalApproved = $reservations->whereNotNull('approved_By')->whereNull('converted_by', 'reserved_by')->count();

        return view('reservations.index', compact('reservations', 'totalReservations', 'totalConverted', 'totalReserved', 'totalCancelled', 'totalApproved'));
    }

    public function create(Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentReservationCode = (Reservation::select('code')->companyReservation()->latest()->first()->code) ?? 0;

        return view('reservations.create', compact('products', 'customers', 'warehouses', 'currentReservationCode'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Reservation $reservation)
    {
        //
    }

    public function edit(Reservation $reservation)
    {
        //
    }

    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    public function destroy(Reservation $reservation)
    {
        //
    }
}
