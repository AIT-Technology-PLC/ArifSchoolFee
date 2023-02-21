<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ReservationDatatable;
use App\DataTables\ReservationDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\MerchandiseBatch;
use App\Models\Reservation;
use App\Models\ReservationDetail;
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

    public function index(ReservationDatatable $datatable)
    {
        $datatable->builder()->setTableId('reservations-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalReservations = Reservation::count();

        $totalConverted = Reservation::converted()->notCancelled()->count();

        $totalReserved = Reservation::reserved()->notConverted()->notCancelled()->count();

        $totalNotApproved = Reservation::notApproved()->notCancelled()->count();

        $totalApproved = Reservation::approved()->notReserved()->notConverted()->notCancelled()->count();

        $totalCancelled = Reservation::cancelled()->count();

        return $datatable->render('reservations.index',
            compact('totalReservations', 'totalConverted', 'totalReserved', 'totalCancelled', 'totalNotApproved', 'totalApproved'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('sales');

        $currentReservationCode = nextReferenceNumber('reservations');

        return view('reservations.create', compact('warehouses', 'currentReservationCode'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = DB::transaction(function () use ($request) {
            $reservation = Reservation::create($request->safe()->except('reservation'));

            $reservationDetails = $reservation->reservationDetails()->createMany($request->validated('reservation'));

            $deletableDetails = collect();

            foreach ($reservationDetails as $reservationDetail) {
                if ($reservationDetail->product->isBatchable() && is_null($reservationDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $reservationDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $reservationDetail->warehouse_id)
                        ->when($reservationDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$reservationDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($reservationDetail->id);

                        $reservation->reservationDetails()->create([
                            'product_id' => $reservationDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $reservationDetail->quantity ? $reservationDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $reservationDetail->original_unit_price,
                            'warehouse_id' => $reservationDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $reservationDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $reservationDetail->quantity - $merchandiseBatch->quantity;
                            $reservationDetail->quantity = $difference;
                        }
                    }
                }
            }

            ReservationDetail::whereIn('id', $deletableDetails)->forceDelete();

            Notification::send(Notifiables::byNextActionPermission('Approve Reservation'), new ReservationPrepared($reservation));

            return $reservation;
        });

        return redirect()->route('reservations.show', $reservation->id);
    }

    public function show(Reservation $reservation, ReservationDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('reservation-details-datatable');

        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse', 'reservationDetails.merchandiseBatch', 'customer', 'contact']);

        return $datatable->render('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $warehouses = authUser()->getAllowedWarehouses('sales');

        $reservation->load(['reservationDetails.product', 'reservationDetails.warehouse', 'reservationDetails.merchandiseBatch']);

        return view('reservations.edit', compact('reservation', 'warehouses'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        [$isExecuted, $message] = $this->reservationService->update(
            $reservation, $request->safe()->except('reservation'), $request->validated('reservation')
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

        abort_if(($reservation->isApproved() || $reservation->isCancelled()) && !authUser()->can('Delete Approved Reservation'), 403);

        $reservation->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
