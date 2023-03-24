<?php

namespace App\Http\Livewire;

use App\Models\Price;
use App\Models\Transaction;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Services\Models\TransactionService;
use App\Traits\PadFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditTransaction extends Component
{
    use AuthorizesRequests, WithFileUploads, PadFileUploads;

    public $transaction;

    public $pad;

    public $masterPadFields;

    public $detailPadFields;

    public $productPadField;

    public $prices;

    public $master;

    public $details;

    public $code;

    public $excludedTransactions;

    public $issued_on;

    public $padStatuses;

    public function mount(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->transaction = $transaction;

        $this->pad = $this->transaction->pad;

        $this->masterPadFields = $this->pad->padFields()->with('padRelation')->masterFields()->get();

        $this->detailPadFields = $this->pad->padFields()->with('padRelation')->detailFields()->get();

        $this->productPadField = $this->detailPadFields->pluck('padRelation')->firstWhere('model_name', 'Product')?->padField;

        $this->prices = Price::active()->get();

        $this->master = $this->transaction->transactionFields()->masterFields()->pluck('value', 'pad_field_id')->toArray();

        $this->details = $this->transaction->transactionFields()->detailFields()->get()->groupBy('line')->map->pluck('value', 'pad_field_id')->values()->all();

        $this->masterPadFieldsTypeFile = collect($this->pad->padFields()->inputTypeFile()->masterFields()->get(['id'])->toArray());

        $this->detailPadFieldsTypeFile = collect($this->pad->padFields()->inputTypeFile()->detailFields()->get(['id'])->toArray());

        $this->code = $this->transaction->code;

        $this->excludedTransactions = Transaction::query()
            ->where('pad_id', '<>', $this->pad->id)
            ->orWhere('id', $this->transaction->id)
            ->pluck('id');

        $this->issued_on = $this->transaction->issued_on->toDateTimeLocalString();

        $this->padStatuses = collect();
    }

    public function addDetail()
    {
        $this->details[] = [];
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);

        $this->details = array_values($this->details);

        $this->dispatchBrowserEvent('select2-removed');
    }

    public function update()
    {
        $descriptionPadField = $this->masterPadFields->firstWhere('label', 'Description');

        if (!$this->transaction->canBeEdited() && $descriptionPadField) {
            $this->transaction
                ->transactionFields()
                ->updateOrCreate(
                    ['pad_field_id' => $descriptionPadField->id],
                    ['value' => $this->validate()['master'][$descriptionPadField->id]]
                );

            return redirect()->route('transactions.show', $this->transaction->id);
        }

        abort_if(!$this->transaction->canBeEdited(), 403);

        $this->authorize('update', $this->transaction);

        DB::transaction(function () {
            (new TransactionService)->update($this->transaction, $this->validate());

            (new TransactionService)->updateFileUploads(
                $this->transaction,
                $this->validatedUploads($this->getDataForValidation($this->rules())['master'], $this->getDataForValidation($this->rules())['details'])
            );
        });

        return redirect()->route('transactions.show', $this->transaction->id);
    }

    public function render()
    {
        return view('livewire.create-edit-transaction');
    }

    protected function rules()
    {
        $rules = [
            'code' => ['required', 'integer', new UniqueReferenceNum('transactions', $this->excludedTransactions)],
            'issued_on' => ['required', 'date'],
        ];

        foreach ($this->masterPadFields as $masterPadField) {
            if ($this->masterPadFieldsTypeFile->where('id', $masterPadField->id)->count()) {
                continue;
            }

            $key = 'master.' . $masterPadField->id;

            $rules[$key][] = $masterPadField->isRequired() ? 'required' : 'nullable';

            $rules[$key][] = 'string';

            if ($masterPadField->hasRelation()) {
                $rules[$key][] = 'integer';
                $rules[$key][] = new MustBelongToCompany(str($masterPadField->padRelation->model_name)->plural()->lower());
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
                $rules[$key][] = 'integer';
                $rules[$key][] = new MustBelongToCompany(str($detailPadField->padRelation->model_name)->plural()->lower());
            }
        }

        return $rules;
    }
}
