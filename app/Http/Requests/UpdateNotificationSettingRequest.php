<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'can_send_payment_reminder' => ['required', 'boolean'],
            'can_send_sms_alert' => ['required', 'boolean'],
            'can_send_email_notification' => ['required', 'boolean'],
            'can_send_push_notification' => ['required', 'boolean'],
            'can_send_system_alert' => ['required', 'boolean'],
        ];
    }
}
