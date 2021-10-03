<?php

namespace App\Traits;

use App\Services\InventoryOperationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait AddInventory
{
    public function add(Request $request, $id)
    {
        $modelName = (string) Str::of($request->path())->before('/')->singular()->ucfirst();

        $modelNameWithPath = (string) Str::of($modelName)->prepend('App\\Models\\');

        $model = $modelNameWithPath::findOrFail($id);

        $this->authorize('add', $model);

        $modelName = $modelName == 'Returnn' ? 'Return' : $modelName;

        $modelDetails = (string) Str::of($modelName)->lower()->append('Details');

        $notificationClass = (string) Str::of($modelName)->append('Added')->prepend('App\\Notifications\\');

        if (!$model->isApproved()) {
            return back()->with('failedMessage', 'This ' . Str::upper($modelName) . ' is not approved.');
        }

        DB::transaction(function () use ($model, $modelDetails, $modelName, $notificationClass) {
            InventoryOperationService::add($model->$modelDetails);

            $model->add();

            $modelName = $modelName == 'Grn' ? Str::of($modelName)->upper() : $modelName;

            Notification::send(
                $this->notifiableUsers('Approve ' . $modelName, $model->createdBy),
                new $notificationClass($model)
            );
        });

        return back();
    }
}
