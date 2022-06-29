<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\NotificationDatatable;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Notification Management');
    }

    public function index(NotificationDatatable $datatable)
    {
        $datatable->builder()->setTableId('notifications-datatable');

        $totalNotifications = authUser()->notifications()->count();

        $totalUnreadNotifications = authUser()->unreadNotifications()->count();

        $totalReadNotifications = $totalNotifications - $totalUnreadNotifications;

        return $datatable->render('notifications.index', compact('totalNotifications', 'totalUnreadNotifications', 'totalReadNotifications'));
    }

    public function show(Notification $notification)
    {
        abort_if($notification->notifiable_id != authUser()->id, 403);

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return redirect($notification->data['endpoint']);
    }

    public function update(Notification $notification)
    {
        abort_if($notification->notifiable_id != authUser()->id, 403);

        $notification->unread() ? $notification->markAsRead() : '';

        return back();
    }

    public function destroy(Notification $notification)
    {
        abort_if($notification->notifiable_id != authUser()->id, 403);

        $notification->forceDelete();

        return back()->with('successMessage', 'Notification deleted successfully');
    }
}
