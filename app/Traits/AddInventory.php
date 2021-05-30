<?php
namespace App\Traits;

use App\Services\InventoryOperationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait AddInventory
{
    use ApproveInventory;

    public function add(Request $request, $id)
    {
        $modelName = (string) Str::of($request->path())->before('/')->singular()->ucfirst();

        $modelNameWithPath = (string) Str::of($modelName)->prepend('App\\Models\\');

        $model = $modelNameWithPath::findOrFail($id);

        $this->authorize('add', $model);

        $modelDetails = (string) Str::of($modelName)->lower()->append('Details');

        $notificationClass = (string) Str::of($modelName)->append('Added')->prepend('App\\Notifications\\');

        if (!$model->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This ' . Str::upper($modelName) . ' is not approved.');
        }

        DB::transaction(function () use ($model, $modelDetails, $modelName, $notificationClass) {
            InventoryOperationService::add($model->$modelDetails);

            $model->add();

            Notification::send(
                $this->notifiableUsers('Approve ' . Str::upper($modelName), $model->createdBy),
                new $notificationClass($model)
            );
        });

        return redirect()->back();
    }
}
