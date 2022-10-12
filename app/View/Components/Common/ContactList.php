<?php

namespace App\View\Components\Common;

use App\Models\Contact;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class ContactList extends Component
{
    public $contacts;

    public $selectedId;

    public $id;

    public $name;

    public $value;

    public function __construct($selectedId, $id = 'contact_id', $name = 'contact_id', $value = 'id')
    {
        $this->contacts = Cache::store('array')
            ->rememberForever(authUser()->id . '_' . 'contactLists', function () {
                return Contact::orderBy('name')->get(['id', 'name']);
            });

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.contact-list');
    }
}
