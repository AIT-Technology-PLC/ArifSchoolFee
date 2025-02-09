<?php

namespace App\Utilities;

use App\Models\Employee;
use App\Models\User;

class Notifiables
{
    private static function isCreatorValid($creator)
    {
        if ($creator instanceof User && $creator->isNot(authUser())) {
            return true;
        }

        return false;
    }

    public static function byNextActionPermission($nextActionPermission, $creator = null)
    {
        if (authUser()->can($nextActionPermission)) {
            return static::isCreatorValid($creator) ? $creator : collect();
        }

        $users = User::query()
            ->permission($nextActionPermission)
            ->whereHas('employee', function ($query) {
                return $query
                    ->where('company_id', userCompany()->id)
                    ->where('id', '<>', authUser()->employee->id);
            })
            ->get();

        if ($users->contains('warehouse_id', authUser()->warehouse_id)) {
            $users = $users->where('warehouse_id', authUser()->warehouse_id);
        }

        if (static::isCreatorValid($creator)) {
            $users->push($creator);
        }

        return $users->unique();
    }

    public static function byPermissionAndWarehouse($permission, $warehouseId, $creator = null)
    {
        if (is_numeric($warehouseId) && $warehouseId == authUser()->warehouse_id) {
            return static::isCreatorValid($creator) ? $creator : collect();
        }

        if (is_countable($warehouseId)) {
            $warehouseId = $warehouseId->filter(fn($id) => $id != authUser()->warehouse_id);
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
            ->where('id', '<>', authUser()->id)
            ->get();

        if (static::isCreatorValid($creator)) {
            $users->push($creator);
        }

        return $users->unique();
    }

    public static function byPermission($permission, $creator = null, $excludeAuthUser = true)
    {
        $users = User::query()
            ->permission($permission)
            ->whereHas('employee', function ($query) use ($excludeAuthUser) {
                return $query
                    ->where('company_id', userCompany()->id)
                    ->when($excludeAuthUser, fn($q) => $q->where('id', '<>', authUser()->employee->id));
            })
            ->get();

        if (static::isCreatorValid($creator)) {
            $users->push($creator);
        }

        return $users->unique();
    }

    public static function byAdminPermission($permission, $creator = null)
    {
        $admins = User::query()
            ->permission($permission)
            ->whereDoesntHave('employee')
            ->get();

        if (static::isCreatorValid($creator)) {
            $admins->push($creator);
        }

        return $admins->unique();
    }
}
