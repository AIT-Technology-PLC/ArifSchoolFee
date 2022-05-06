<?php

namespace App\Http\Livewire;

use App\Models\Pad;
use Livewire\Component;

class CreateTransaction extends Component
{
    public $pad, $currentReferenceCode, $masterPadFields, $detailPadFields, $details;

    public function mount(Pad $pad)
    {
        $this->pad = $pad;

        $this->currentReferenceCode = nextReferenceNumber('transactions');

        $this->masterPadFields = $this->pad->padFields()->with('padRelation')->where('is_master_field', 1)->get();

        $this->detailPadFields = $this->pad->padFields()->with('padRelation')->where('is_master_field', 0)->get();

        $this->details = [
            [],
        ];
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

    public function render()
    {
        return view('livewire.create-transaction');
    }
}
