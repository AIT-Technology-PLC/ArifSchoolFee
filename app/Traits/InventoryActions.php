<?php
namespace App\Traits;

use App\Notifications\GdnApproved;
use App\Notifications\GdnSubtracted;
use App\Notifications\GrnAdded;
use App\Notifications\GrnApproved;
use App\Notifications\TransferApproved;
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

        $notification = (string) Str::of($modelName)->lower()->append('ApprovedNotification');

        $modelName = $modelName == 'Gdn' ? 'Do/Gdn' : $modelName;

        $message = 'This ' . Str::upper($modelName) . ' is already approved';

        if (!$model->isApproved()) {
            $message = DB::transaction(function () use ($model, $modelName, $notification) {
                $model->approve();

                $this->$notification($model);

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

        $notification = (string) Str::of($modelName)->lower()->append('SubtractedNotification');

        $modelName = $modelName == 'Gdn' ? 'Do/Gdn' : $modelName;

        if (!$model->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This ' . Str::upper($modelName) . ' is not approved');
        }

        $result = DB::transaction(function () use ($model, $modelDetails, $notification) {
            $result = InventoryOperationService::subtract($model->$modelDetails);

            if (!$result['isSubtracted']) {
                DB::rollBack();

                return $result;
            }

            $model->subtract();

            $this->$notification($model);

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

        $notification = (string) Str::of($modelName)->lower()->append('AddedNotification');

        if (!$model->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This ' . Str::upper($modelName) . ' is not approved.');
        }

        DB::transaction(function () use ($model, $modelDetails, $notification) {
            InventoryOperationService::add($model->$modelDetails);

            $model->add();

            $this->$notification($model);
        });

        return redirect()->back();
    }

    public function gdnSubtractedNotification($model)
    {
        Notification::send($this->notifiableUsers('Approve GDN'), new GdnSubtracted($model));

        Notification::send($this->notifyCreator($model, $this->notifiableUsers('Approve GDN')), new GdnSubtracted($model));
    }

    public function gdnApprovedNotification($model)
    {
        Notification::send($this->notifiableUsers('Subtract GDN'), new GdnApproved($model));

        Notification::send($this->notifyCreator($model, $this->notifiableUsers('Subtract GDN')), new GdnApproved($model));
    }

    public function grnApprovedNotification($model)
    {
        Notification::send($this->notifiableUsers('Add GRN'), new GrnApproved($model));

        Notification::send($this->notifyCreator($model, $this->notifiableUsers('Add GRN')), new GrnApproved($model));
    }

    public function grnAddedNotification($model)
    {
        Notification::send($this->notifiableUsers('Approve GRN'), new GrnAdded($model));

        Notification::send($this->notifyCreator($model, $this->notifiableUsers('Approve GRN')), new GrnAdded($model));
    }

    public function transferApprovedNotification($model)
    {
        Notification::send($this->notifiableUsers('Make Transfer'), new TransferApproved($model));

        Notification::send($this->notifyCreator($model, $this->notifiableUsers('Make Transfer')), new TransferApproved($model));
    }
}
