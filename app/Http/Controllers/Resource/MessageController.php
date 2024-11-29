<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\MessageDatatable;
use App\DataTables\MessageDetailDatatable;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Http\Requests\FilterRequest;
use App\Services\Models\MessageService;

class MessageController extends Controller
{
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->middleware('isFeatureAccessible:Email/SMS Management');

        $this->authorizeResource(Message::class, 'message');

        $this->messageService = $messageService;
    }

    public function index(MessageDatatable $datatable)
    {
        $datatable->builder()->setTableId('messages-datatable')->orderBy(1, 'asc');

        $totalMessages = Message::count();

        return $datatable->render('messages.index', compact('totalMessages'));
    }

    public function create(FilterRequest $request)
    {
        if (!userCompany()->canSendEmailNotification() && !userCompany()->canSendSmsAlert()) {
            return back()->with('failedMessage', 'Unable to send email or SMS because the email and SMS settings are turned off !');
        }

        $data = $this->messageService->create($request);

        return view('messages.create', $data);
    }

    public function show(Message $message, MessageDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('message-details-datatable');

        $message->load(['messageDetails.employee', 'messageDetails.student', 'messageDetails.staff']);

        return $datatable->render('messages.show', compact('message'));
    }

    public function store(StoreMessageRequest $request)
    {
        [$isExecuted, $message] = $this->messageService->store($request);

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('messages.index')->with('successMessage', 'Message Successfully Sent!');
    }
}
