<?php

namespace App\Http\Livewire;

use App\Models\PadField;
use Livewire\Component;

class TransactionDetailForm extends Component
{
    public $padFields, $details, $padId;

    public function mount()
    {
        $this->padFields = PadField::query()
            ->where('pad_id', $this->padId)
            ->where('is_master_field', 0)
            ->get();

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
    }

    public function render()
    {
        return view('livewire.transaction-detail-form');
    }
}
