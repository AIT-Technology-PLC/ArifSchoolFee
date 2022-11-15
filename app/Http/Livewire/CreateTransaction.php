<?php

namespace App\Http\Livewire;

use App\Models\Pad;
use App\Models\Transaction;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Services\Models\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateTransaction extends Component
{
    use AuthorizesRequests;

    public $pad;

    public $currentReferenceCode;

    public $masterPadFields;

    public $detailPadFields;

    public $master;

    public $details;

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

        $this->master = $master;

        $this->details = $details;

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

        $this->dispatchBrowserEvent('select2-removed');
    }

    public function store()
    {
        $this->authorize('create', [Transaction::class, $this->pad]);

        $transaction = (new TransactionService)->store($this->pad, $this->validate());

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
