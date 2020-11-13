<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->authorizeResource(Permission::class);

        $this->permission = $permission;
    }

    public function show(Permission $permission)
    {
        //
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = request()->validate([
            'settings' => 'sometimes|nullable|array',
            'warehouses' => 'sometimes|nullable|array',
            'products' => 'sometimes|nullable|array',
            'merchandises' => 'sometimes|nullable|array',
            'manufacturings' => 'sometimes|nullable|array',
        ]);

        foreach ($data as $key => $value) {
            $permission->update([
                $key => implode($value),
            ]);
        }

        return redirect('/employees');
    }
}
