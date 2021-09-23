<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait ApproveInventory
{
    public function approve(Request $request, $id)
    {
        $modelName = (string) Str::of($request->path())->before('/')->singular()->studly();

        $modelNameWithPath = (string) Str::of($modelName)->prepend('App\\Models\\');

        $model = $modelNameWithPath::findOrFail($id);

        $this->authorize('approve', $model);

        $modelName = $modelName == 'Returnn' ? 'Return' : $modelName;

        $notificationClass = (string) Str::of($modelName)->append('Approved')->prepend('App\\Notifications\\');

        $modelName = $modelName == 'Gdn' ? 'DO' : $modelName;

        $message = 'This ' . Str::upper($modelName) . ' is already approved';

        if (!$model->isApproved()) {
            $message = DB::transaction(function () use ($model, $modelName, $notificationClass) {
                $model->approve();

                if ($this->permission) {
                    Notification::send(
                        $this->notifiableUsers($this->permission, $model->createdBy),
                        new $notificationClass($model)
                    );
                }

                return 'You have approved this ' . Str::upper($modelName) . ' successfully';
            });
        }

        return redirect()->back()->with('successMessage', $message);
    }
}
