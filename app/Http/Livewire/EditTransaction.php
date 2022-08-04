<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Services\Models\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditTransaction extends Component
{
    use AuthorizesRequests;

    public $transaction;

    public $pad;

    public $masterPadFields;

    public $detailPadFields;

    public $master;

    public $details;

    public $code;

    public $excludedTransactions;

    public $issued_on;

    public $status;

    public $padStatuses;

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;

        $this->pad = $this->transaction->pad;

        $this->masterPadFields = $this->pad->padFields()->with('padRelation')->masterFields()->get();

        $this->detailPadFields = $this->pad->padFields()->with('padRelation')->detailFields()->get();

        $this->master = $this->transaction->transactionFields()->masterFields()->pluck('value', 'pad_field_id')->toArray();

        $this->details = $this->transaction->transactionFields()->detailFields()->get()->groupBy('line')->map->pluck('value', 'pad_field_id')->toArray();

        $this->code = $this->transaction->code;

        $this->excludedTransactions = Transaction::query()
            ->where('pad_id', '<>', $this->pad->id)
            ->orWhere('id', $this->transaction->id)
            ->pluck('id');

        $this->issued_on = $this->transaction->issued_on->toDateTimeLocalString();

        $this->status = $transaction->status ?? '';

        $this->padStatuses = $transaction->pad->padStatuses()->active()->get();
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
        abort_if(!$this->transaction>->canBeEdited(), 403);

        $this->authorize('update', $this->transaction);

        (new TransactionService)->update($this->transaction, $this->validate());

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
            'status' => [Rule::requiredIf($this->padStatuses->isNotEmpty()), 'string'],
        ];

        foreach ($this->masterPadFields as $masterPadField) {
            $key = 'master.' . $masterPadField->id;

            $rules[$key][] = $masterPadField->isRequired() ? 'required' : 'nullable';

            $rules[$key][] = 'string';

            if ($masterPadField->hasRelation()) {
                $rules[$key][] = 'integer';
                $rules[$key][] = new MustBelongToCompany(str($masterPadField->padRelation->model_name)->plural()->lower());
            }
        }

        foreach ($this->detailPadFields as $detailPadField) {
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
