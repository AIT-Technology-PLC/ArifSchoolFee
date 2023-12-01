<?php

namespace App\Reports;

use App\Models as Models;
use App\Models\Transaction;
use Illuminate\Support\Arr;

class FeatureReport
{
    private $company;

    private $onlyToday;

    function __construct($company = null, $onlyToday = false)
    {
        $this->company = $company;

        $this->onlyToday = $onlyToday;
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function transactionalFeatures()
    {
        $features = [
            Models\Adjustment::class => 'notAdjusted',
            Models\BillOfMaterial::class => 'notApproved',
            Models\Credit::class => 'unsettled',
            Models\CustomerDeposit::class => 'notApproved',
            Models\Damage::class => 'notSubtracted',
            Models\Debt::class => 'unsettled',
            Models\Exchange::class => 'notApproved',
            Models\Gdn::class => 'notSubtracted',
            Models\Grn::class => 'notAdded',
            Models\Job::class => 'notApproved',
            Models\ProformaInvoice::class => 'pending',
            Models\Purchase::class => 'notPurchased',
            Models\Reservation::class => 'notReserved',
            Models\Returnn::class => 'notAdded',
            Models\Sale::class => 'notApproved',
            Models\Siv::class => 'notApproved',
            Models\Transfer::class => 'notSubtracted',
        ];

        foreach ($features as $key => $value) {
            $model = new $key;

            $data[] = [
                'feature' => $model->getTable(),
                'total_transactions' => $model->when($this->onlyToday, fn($q) => $q->whereDate('created_at', today()))->when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->count(),
                'incomplete_transactions' => $model->when($this->onlyToday, fn($q) => $q->whereDate('created_at', today()))->$value()->when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->count(),
            ];
        }

        return Arr::sortDesc($data, 'total_transactions');
    }

    public function masterFeatures()
    {
        $features = [
            Models\Brand::class,
            Models\Contact::class,
            Models\Customer::class,
            Models\Product::class,
            Models\ProductCategory::class,
            Models\Supplier::class,
        ];

        foreach ($features as $feature) {
            $model = new $feature;

            $data[] = [
                'feature' => $model->getTable(),
                'total' => $model->when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->count(),
            ];
        }

        return Arr::sortDesc($data, 'total');
    }

    public function padFeatures()
    {
        $data = [];

        $pads = Models\Pad::when(!is_null($this->company), fn($q) => $q->where('company_id', $this->company->id))->get();

        foreach ($pads as $pad) {
            $incompleteTransactions = 0;

            if ($pad->isInventoryOperationAdd()) {
                $incompleteTransactions = Transaction::query()
                    ->when($this->onlyToday, fn($q) => $q->whereDate('created_at', today()))
                    ->where('pad_id', $pad->id)
                    ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'added_by'))
                    ->count();
            }

            if ($pad->isInventoryOperationSubtract()) {
                $incompleteTransactions = Transaction::query()
                    ->when($this->onlyToday, fn($q) => $q->whereDate('created_at', today()))
                    ->where('pad_id', $pad->id)
                    ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'subtracted_by'))
                    ->count();
            }

            if ($pad->isApprovable() && $pad->isInventoryOperationNone()) {
                $incompleteTransactions = Transaction::query()
                    ->when($this->onlyToday, fn($q) => $q->whereDate('created_at', today()))
                    ->where('pad_id', $pad->id)
                    ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'approved_by'))
                    ->count();
            }

            $data[] = [
                'feature' => $pad->name,
                'total_transactions' => $pad->transactions()->when($this->onlyToday, fn($q) => $q->whereDate('created_at', today()))->count(),
                'incomplete_transactions' => $incompleteTransactions,
            ];
        }

        return Arr::sortDesc($data, 'total_transactions');
    }
}
