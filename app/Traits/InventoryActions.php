<?php
namespace App\Traits;

use App\Services\InventoryOperationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait InventoryActions
{
    public function approve(Request $request, $id)
    {
        $modelName = (string) Str::of($request->path())->before('/')->singular()->ucfirst();

        $modelNameWithPath = (string) Str::of($modelName)->prepend('App\\Models\\');

        $model = $modelNameWithPath::findOrFail($id);

        $this->authorize('approve', $model);

        $notificationClass = (string) Str::of($modelName)->append('Approved')->prepend('App\\Notifications\\');

        $modelName = $modelName == 'Gdn' ? 'Do/Gdn' : $modelName;

        $message = 'This ' . Str::upper($modelName) . ' is already approved';

        if (!$model->isApproved()) {
            $message = DB::transaction(function () use ($model, $modelName, $notificationClass) {
                $model->approve();

                Notification::send(
                    $this->notifiableUsers($this->permission, $model->createdBy),
                    new $notificationClass($model)
                );

                return 'You have approved this ' . Str::upper($modelName) . ' successfully';
            });
        }

        return redirect()->back()->with('successMessage', $message);
    }

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
            $result = InventoryOperationService::subtract($model->$modelDetails);

            if (!$result['isSubtracted']) {
                DB::rollBack();

                return $result;
            }

            $model->subtract();

            Notification::send(
                $this->notifiableUsers('Approve ' . Str::of($modelName)->remove('Do/')->upper(), $model->createdBy),
                new $notificationClass($model)
            );

            return $result;
        });

        return $result['isSubtracted'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }

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
