<?php

namespace App\Utilities;

use App\Models\User;

class Notifiables
{
    private $notifiables;

    public function __construct($notifiables = [])
    {
        $this->notifiables = collect($notifiables);
    }

    public function with(...$users)
    {
        return $this->notifiables
            ->push(...$users)
            ->unique()
            ->filter
            ->isNot(auth()->user());
    }

    public static function nextAction($nextActionPermission)
    {
        if (auth()->user()->can($nextActionPermission)) {
            return new static;
        }

        $users = User::query()
            ->permission($nextActionPermission)
            ->whereHas('employee', function ($query) {
                return $query
                    ->where('company_id', userCompany()->id)
                    ->where('id', '<>', auth()->user()->employee->id);
            })
            ->get();

        if ($users->contains('warehouse_id', auth()->user()->warehouse_id)) {
            $users = $users->where('warehouse_id', auth()->user()->warehouse_id);
        }

        return new static($users);
    }

    public static function branch($permission, $warehouseId)
    {
        if (is_numeric($warehouseId) && $warehouseId == auth()->user()->warehouse_id) {
            return new static;
        }

        if (is_countable($warehouseId)) {
            $warehouseId = $warehouseId->filter(fn($id) => $id != auth()->user()->warehouse_id);
        }

        $users = User::query()
            ->permission($permission)
            ->when(
                is_countable($warehouseId),
                function ($query) use ($warehouseId) {
                    return $query->whereIn('warehouse_id', $warehouseId);
                },
                function ($query) use ($warehouseId) {
                    return $query->where('warehouse_id', $warehouseId);
                }
            )
            ->where('id', '<>', auth()->id())
            ->get();

        return new static($users);
    }
}
