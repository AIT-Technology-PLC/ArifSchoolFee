<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\NoticeDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoticeRequest;
use App\Models\Notice;
use App\Models\Warehouse;
use Spatie\Permission\Models\Role;
use App\Services\Models\NoticeService;

class NoticeController extends Controller
{
    private $noticeService;

    public function __construct(NoticeService $noticeService)
    {
        $this->middleware('isFeatureAccessible:Notice Management');

        $this->authorizeResource(Notice::class);

        $this->noticeService = $noticeService;
    }

    public function index(NoticeDatatable $datatable)
    {
        $datatable->builder()->setTableId('notices-datatable')->orderBy(1, 'desc');

        return $datatable->render('notices.index');
    }

    public function create()
    {
        if (!userCompany()->canSendPushNotification())
            {
                return back()->with('failedMessage', 'Unable to add a notice because the push notification setting are turned off !');
            }

        $roles = Role::orderBy('name')->get(['id', 'name']);

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        return view('notices.create', compact('branches','roles'));
    }

    public function store(StoreNoticeRequest $request)
    {
        $notice = $this->noticeService->send($request);

        return redirect()->route('notices.show', $notice->id)->with('successMessage', 'Notice Sent Successfully');
    }

    public function show(Notice $notice)
    {
        $notice->load(['warehouses', 'recipientTypes']);

        return view('notices.show', compact('notice'));
    }
}
