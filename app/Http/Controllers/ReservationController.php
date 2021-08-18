<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Reservation;
use App\Models\Warehouse;
use App\Notifications\GdnPrepared;
use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationConverted;
use App\Notifications\ReservationMade;
use App\Notifications\ReservationPrepared;
use App\Services\InventoryOperationService;
use App\Traits\NotifiableUsers;
use App\Traits\PrependCompanyId;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    use NotifiableUsers, SubtractInventory, PrependCompanyId;

    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Reservation Management');

        $this->authorizeResource(Reservation::class, 'reservation');

        $this->permission = 'Make Reservation';
    }

    public function index()
    {
        $reservations = Reservation::companyReservation()
            ->with(['reservationDetails', 'reservable', 'createdBy', 'updatedBy', 'approvedBy', 'company', 'customer'])
            ->latest()->get();

        $totalReservations = $reservations->count();

        $totalConverted = $reservations->whereNotNull('converted_by')
            ->whereNull('cancelled_by')->count();

        $totalReserved = $reservations->whereNotNull('reserved_by')->whereNull('converted_by')
            ->whereNull('cancelled_by')->count();

        $totalNotApproved = $reservations->whereNull('approved_by')
            ->whereNull('cancelled_by')->count();

        $totalApproved = $reservations->whereNotNull('approved_by')->whereNull('reserved_by')->whereNull('converted_by')
            ->whereNull('cancelled_by')->count();

        $totalCancelled = $reservations->whereNotNull('cancelled_by')->count();

        $totalReservedInBirr = $reservations->whereNotNull('reserved_by')->whereNull('converted_by')
            ->whereNull('cancelled_by')->sum('total_price_with_vat');

        return view('reservations.index', compact('reservations', 'totalReservations', 'totalConverted', 'totalReserved', 'totalCancelled', 'totalNotApproved', 'totalApproved', 'totalReservedInBirr'));
    }

    public function create(Customer $customer, Warehouse $warehouse)
    {
        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentReservationCode = (Reservation::select('code')->companyReservation()->latest()->first()->code) ?? 0;

        return view('reservations.create', compact('customers', 'warehouses', 'currentReservationCode'));
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

    public function edit(Reservation $reservation, Customer $customer, Warehouse $warehouse)
    {
        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse']);

        return view('reservations.edit', compact('reservation', 'customers', 'warehouses'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        if ($reservation->isCancelled() || $reservation->isConverted()) {
            return redirect()->route('reservations.show', $reservation->id);
        }

        DB::transaction(function () use ($request, $reservation) {
            if ($reservation->isReserved()) {
                InventoryOperationService::cancelReservation($reservation->reservationDetails);
                Notification::send($this->notifiableUsers('Approve Reservation'), new ReservationPrepared($reservation));
            }

            $reservation->update($request->except('reservation'));

            for ($i = 0; $i < count($request->reservation); $i++) {
                $reservation->reservationDetails[$i]->update($request->reservation[$i]);
            }
        });

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function destroy(Reservation $reservation)
    {
        if (($reservation->isConverted() || $reservation->isReserved()) && !$reservation->isCancelled()) {
            return view('errors.permission_denied');
        }

        if (($reservation->isApproved() || $reservation->isCancelled()) && !auth()->user()->can('Delete Approved Reservation')) {
            return view('errors.permission_denied');
        }

        $reservation->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function reserve(Reservation $reservation)
    {
        $this->authorize('reserve', $reservation);

        if (!$reservation->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This reservation is not approved yet.');
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

    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        if (!$reservation->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This reservation is not approved yet.');
        }

        if ($reservation->isConverted() && $reservation->reservable->isSubtracted()) {
            return redirect()->back()->with('failedMessage', 'This reservation cannot be cancelled, it has been converted to sales.');
        }

        if (!$reservation->isConverted() && !$reservation->isReserved()) {
            $reservation->cancel();

            return redirect()->back();
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

        return redirect()->back();
    }

    public function convert(Reservation $reservation)
    {
        $this->authorize('convert', $reservation);

        $this->authorize('create', Gdn::class);

        if (!$reservation->isReserved()) {
            return redirect()->back()->with('failedMessage', 'This reservation is not reserved yet.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            Notification::send(
                $this->notifiableUsers('Approve Reservation', $reservation->createdBy),
                new ReservationConverted($reservation)
            );

            $currentGdnCode = (Gdn::select('code')->companyGdn()->latest()->first()->code) ?? 0;

            $gdn = Gdn::create([
                'status' => 'Not Subtracted From Inventory',
                'code' => $this->prependCompanyId($currentGdnCode + 1),
                'customer_id' => $reservation->customer_id ?? null,
                'issued_on' => today(),
                'payment_type' => $reservation->payment_type,
                'description' => $reservation->description ?? '',
                'cash_received_in_percentage' => $reservation->cash_received_in_percentage,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'company_id' => userCompany()->id,
            ]);

            $gdn->gdnDetails()->createMany($reservation->reservationDetails->toArray());

            $gdn->reservation()->save($reservation);

            Notification::send(
                $this->notifiableUsers('Approve GDN', $gdn->createdBy),
                new GdnPrepared($gdn)
            );
        });

        return redirect()->back();
    }
}
