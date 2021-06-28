<?php
namespace App\Traits;

use App\Services\InventoryOperationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait SubtractInventory
{
    use ApproveInventory;

    public function subtract(Request $request, $id)
    {
        $modelName = (string) Str::of($request->path())->before('/')->singular()->ucfirst();

        $modelNameWithPath = (string) Str::of($modelName)->prepend('App\\Models\\');

        $model = $modelNameWithPath::findOrFail($id);

        $this->authorize('subtract', $model);

        $modelDetails = (string) Str::of($modelName)->lower()->append('Details');

        $notificationClass = (string) Str::of($modelName)->append('Subtracted')->prepend('App\\Notifications\\');

        $modelName = $modelName == 'Gdn' ? 'Do/Gdn' : $modelName;

        if (!$model->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This ' . Str::upper($modelName) . ' is not approved');
        }

        $result = DB::transaction(function () use ($model, $modelDetails, $modelName, $notificationClass) {
            $from = $model->reservation ? 'reserved' : 'available';

            $result = InventoryOperationService::subtract($model->$modelDetails, $from);

            if (!$result['isSubtracted']) {
                DB::rollBack();

                return $result;
            }

            $model->subtract();

            $modelName = $modelName == 'Do/Gdn' ? Str::of($modelName)->remove('Do/')->upper() : $modelName;

            Notification::send(
                $this->notifiableUsers('Approve ' . $modelName, $model->createdBy),
                new $notificationClass($model)
            );

            return $result;
        });

        return $result['isSubtracted'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }
}
