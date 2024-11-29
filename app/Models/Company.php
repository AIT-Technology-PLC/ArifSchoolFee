<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'integer',
        'enabled_commission_setting' => 'integer',
        'can_show_branch_detail_on_print' => 'integer',
        'can_send_payment_reminder' => 'integer',
        'can_send_sms_alert' => 'integer',
        'can_send_email_notification' => 'integer',
        'can_send_push_notification' => 'integer',
        'can_send_system_alert' => 'integer',
        'is_in_training' => 'integer',
        'subscription_expires_on' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class);
    }

    public function limits()
    {
        return $this->morphToMany(Limit::class, 'limitable')->withPivot('amount');
    }

    public function features()
    {
        return $this->morphToMany(Feature::class, 'featurable')->withPivot('is_enabled');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function pads()
    {
        return $this->hasMany(Pad::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function schoolClasses()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function studentCategories()
    {
        return $this->hasMany(StudentCategory::class);
    }

    public function studentGroups()
    {
        return $this->hasMany(StudentGroup::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function feeGroups()
    {
        return $this->hasMany(FeeGroup::class);
    }

    public function feeTypes()
    {
        return $this->hasMany(FeeType::class);
    }

    public function feeDiscounts()
    {
        return $this->hasMany(FeeDiscount::class);
    }

    public function feeMasters()
    {
        return $this->hasMany(FeeMaster::class);
    }

    public function assignFeeMastsers()
    {
        return $this->hasMany(AssignFeeMaster::class);
    }

    public function assignFeeDiscounts()
    {
        return $this->hasMany(AssignFeeDiscount::class);
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }
    
    public function userLogs()
    {
        return $this->hasMany(UserLog::class);
    }
     
    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }

    public function scopeExpiredSubscriptions($query)
    {
        return $query->whereNotNull('subscription_expires_on')->whereDate('subscription_expires_on', '<', today());
    }

    public function scopeActiveSubscriptions($query)
    {
        return $query->whereNotNull('subscription_expires_on')->whereDate('subscription_expires_on', '>=', today());
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str()->lower($value) ?? ''
        );
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function canShowBranchDetailOnPrint()
    {
        return $this->can_show_branch_detail_on_print;
    }

    public function canSendPaymentReminder()
    {
        return $this->can_send_payment_reminder;
    }

    public function canSendSmsAlert()
    {
        return $this->can_send_sms_alert;
    }

    public function canSendEmailNotification()
    {
        return $this->can_send_email_notification;
    }

    public function canSendPushNotification()
    {
        return $this->can_send_push_notification;
    }

    public function canSendSystemAlert()
    {
        return $this->can_send_system_alert;
    }

    public function hasPrintTemplate()
    {
        return $this->print_template_image;
    }

    public function toggleActivation()
    {
        $this->isEnabled() ? $this->enabled = 0 : $this->enabled = 1;

        $this->save();
    }

    public function deactivate()
    {
        $this->enabled = 0;

        $this->save();
    }

    public function isInTraining()
    {
        return $this->is_in_training;
    }

    public function subscribe($subscription)
    {
        $this->subscription_expires_on = $subscription->expiresOn;

        $this->plan_id = $subscription->plan_id;

        $this->enabled = 0;

        $this->save();
    }

    public function canCreateNewSubscription()
    {
        return is_null($this->subscription_expires_on) || $this->isSubscriptionNearExpiry();
    }

    public function isSubscriptionNearExpiry()
    {
        if (is_null($this->subscription_expires_on)) {
            return false;
        }

        return today()->diffInDays($this->subscription_expires_on, false) <= 45;
    }
}
