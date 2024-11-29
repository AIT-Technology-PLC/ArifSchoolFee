<?php

namespace App\Services\Models;

use Illuminate\Support\Facades\DB;
use App\Models\Notice;
use App\Models\User;
use App\Notifications\SendNoticeMessage;

class NoticeService
{
    public function send($request)
    {
        $notice = DB::transaction(function () use ($request) {
            $notice = Notice::create($request->safe()->except('type','warehouse_id'));
    
            $notice->warehouses()->sync($request->validated('warehouse_id'));
    
            $recipientTypes = collect($request->validated('type'))->map(function ($type) {
                return ['type' => $type];
            })->toArray();
    
            $notice->recipientTypes()->createMany($recipientTypes);

            if (userCompany()->canSendPushNotification()) {
                $users = $this->getEligibleUsers($request);

                foreach ($users as $user) {
                    $user->notify(new SendNoticeMessage($notice));
                }
            }

            return $notice;
        });

        return $notice;
    }

    public function getEligibleUsers($request)
    {
        $users = User::query()
            ->whereIn('warehouse_id', $request->validated('warehouse_id'))
            ->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', $request->validated('type'));
            })
            ->get();

        return $users;
    }
}