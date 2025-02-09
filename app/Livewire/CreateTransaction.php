<?php

namespace App\Livewire;

use App\Models\Merchandise;
use App\Models\Pad;
use App\Models\Price;
use App\Models\Product;
use App\Models\Transaction;
use App\Notifications\TransactionPrepared;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Services\Models\TransactionService;
use App\Traits\PadFileUploads;
use App\Utilities\Notifiables;
use App\Utilities\PadBatchSelectionIsRequiredOrProhibited;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTransaction extends Component
{
    use AuthorizesRequests, WithFileUploads, PadFileUploads;

    public $pad;

    public $currentReferenceCode;

    public $masterPadFields;

    public $detailPadFields;

    public $productPadField;

    public $warehousePadField;

    public $prices;

    public $merchandises;

    public $products;

    public $master;

    public $details;

    public $masterPadFieldsTypeFile;

    public $detailPadFieldsTypeFile;

    public $code;

    public $excludedTransactions;

    public $issued_on;

    public $status = '';

    public $padStatuses;

    public function mount(Pad $pad, $master, $details)
    {
        abort_if(!$pad->isEnabled(), 403);

        $this->pad = $pad;

        $this->currentReferenceCode = $this->pad->transactions()->max('code') + 1;

        $this->masterPadFields = $this->pad->padFields()->with('padRelation')->masterFields()->get();

        $this->detailPadFields = $this->pad->padFields()->with('padRelation')->detailFields()->get();

        $this->productPadField = $this->detailPadFields->pluck('padRelation')->firstWhere('model_name', 'Product')?->padField;

        $this->warehousePadField = $this->detailPadFields->pluck('padRelation')->firstWhere('model_name', 'Warehouse')?->padField;

        $this->prices = Price::active()->get();

        $this->merchandises = Merchandise::all();

        $this->products = Product::active()->get();

        $this->master = $master;

        $this->details = $details;

        $this->masterPadFieldsTypeFile = collect($this->pad->padFields()->inputTypeFile()->masterFields()->get(['id'])->toArray());

        $this->detailPadFieldsTypeFile = collect($this->pad->padFields()->inputTypeFile()->detailFields()->get(['id'])->toArray());

        $this->code = $this->currentReferenceCode;

        $this->excludedTransactions = Transaction::where('pad_id', '<>', $this->pad->id)->pluck('id');

        $this->issued_on = now()->toDateTimeLocalString();

        $this->padStatuses = $pad->padStatuses()->active()->get();
    }

    public function addDetail()
    {
        $this->details[] = [];
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);

        $this->details = array_values($this->details);

        $this->dispatch('select2-removed');
    }

    public function store()
    {
        $this->authorize('create', [Transaction::class, $this->pad]);

        $transaction = DB::transaction(function () {
            $transaction = (new TransactionService)->store($this->pad, $this->validate());

            (new TransactionService)->updateFileUploads(
                $transaction,
                $this->validatedUploads($this->getDataForValidation($this->rules())['master'], $this->getDataForValidation($this->rules())['details'])
            );

            Notification::send(Notifiables::forPad($transaction->pad), new TransactionPrepared($transaction));

            return $transaction;
        });

        return redirect()->route('transactions.show', $transaction->id);
    }

    public function render()
    {
        return view('livewire.create-edit-transaction');
    }

    protected function rules()
    {
        $rules = [
            'code' => ['required', 'integer', new UniqueReferenceNum('transactions', $this->excludedTransactions)],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'status' => [Rule::requiredIf($this->padStatuses->isNotEmpty()), 'string'],
        ];

        if ($this->pad->hasDetailPadFields()) {
            $rules['details'] = ['required', 'array'];
        }

        foreach ($this->masterPadFields as $masterPadField) {
            if ($this->masterPadFieldsTypeFile->where('id', $masterPadField->id)->count()) {
                continue;
            }

            $key = 'master.' . $masterPadField->id;

            $rules[$key][] = $masterPadField->isRequired() ? 'required' : 'nullable';

            $rules[$key][] = 'string';

            if ($masterPadField->hasRelation()) {
                array_pop($rules[$key]);
                $rules[$key][] = 'integer';
                $rules[$key][] = new MustBelongToCompany(str($masterPadField->padRelation->model_name)->plural()->lower()->snake());
            }
        }

        foreach ($this->detailPadFields as $detailPadField) {
            if ($this->detailPadFieldsTypeFile->where('id', $detailPadField->id)->count()) {
                continue;
            }

            $key = 'details.*.' . $detailPadField->id;

            $rules[$key][] = $detailPadField->isRequired() ? 'required' : 'nullable';

            $rules[$key][] = 'string';

            if ($detailPadField->hasRelation()) {
                array_pop($rules[$key]);
                $rules[$key][] = 'integer';
                $rules[$key][] = new MustBelongToCompany(str($detailPadField->padRelation->model_name)->plural()->lower()->snake());
            }

            if ($detailPadField->isBatchNoField() || $detailPadField->isMerchandiseBatchField()) {
                unset($rules[$key][array_search('string', $rules[$key])]);
                unset($rules[$key][array_search('nullable', $rules[$key])]);
                $rules[$key][] = Rule::foreach(
                    fn($v, $a) =>
                    (new PadBatchSelectionIsRequiredOrProhibited(
                        !$this->pad->isInventoryOperationAdd(),
                        $this->getDataForValidation($this->rules())['details'],
                        $this->productPadField
                    ))
                    ->passes($a, $v)
                );
            }
        }

        return $rules;
    }
}
