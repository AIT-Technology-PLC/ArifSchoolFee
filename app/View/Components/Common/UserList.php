<?php

namespace App\View\Components\Common;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class UserList extends Component
{
    public $users, $selectedId, $id, $name, $value;

    public function __construct($selectedId, $id = 'user_id', $name = 'user_id', $value = 'id')
    {
        $this->users = Cache::store('array')->rememberForever(authUser()->id . '_' . 'userLists', function () {
            return User::query()
                ->whereRelation('employee', 'company_id', '=', userCompany()->id)
                ->orderBy('name')
                ->get(['id', 'name']);
        });

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.user-list');
    }
}
