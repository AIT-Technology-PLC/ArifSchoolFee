<?php

namespace App\Http\Livewire;

use App\Models\Pad;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Livewire\Component;

class CreateTransaction extends Component
{
    public $pad, $currentReferenceCode, $masterPadFields, $detailPadFields, $master, $details, $code, $issued_on;

    public function mount(Pad $pad)
    {
        $this->pad = $pad;

        $this->currentReferenceCode = nextReferenceNumber('transactions');

        $this->masterPadFields = $this->pad->padFields()->with('padRelation')->where('is_master_field', 1)->get();

        $this->detailPadFields = $this->pad->padFields()->with('padRelation')->where('is_master_field', 0)->get();

        $this->master = [];

        $this->details = [
            [],
        ];

        $this->code = $this->currentReferenceCode;

        $this->issued_on = now()->toDateTimeLocalString();
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
        $this->validate();
    }

    public function render()
    {
        return view('livewire.create-transaction');
    }

    protected function rules()
    {
        $rules = [
            'code' => ['required', 'integer', new UniqueReferenceNum('transactions')],
            'issued_on' => ['required', 'date'],
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
