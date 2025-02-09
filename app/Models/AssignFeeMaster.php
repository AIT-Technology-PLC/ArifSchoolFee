<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssignFeeMaster extends Model
{
    use MultiTenancy, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Assign Fee Master');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeMaster()
    {
        return $this->belongsTo(FeeMaster::class);
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class, 'assign_fee_master_id');
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getFineAmount()
    {
        $feeMaster = $this->feeMaster;
        
        if (!$feeMaster) {
            return 0;
        }

        if (now()->gt($feeMaster->due_date)) {
            $fineAmount = 0;

            if ($feeMaster->fine_type === 'amount') {
                $fineAmount = $feeMaster->fine_amount;
            } elseif ($feeMaster->fine_type === 'percentage') {
                $fineAmount = ($feeMaster->fine_amount / 100) * $feeMaster->amount;
            }

            return $fineAmount;
        }

        return 0;
    }

    public function getExchangeRate()
    {
        $company = $this->company;

        if (!$company || $company->currency === 'Br') {
            return null;
        }

        return DB::table('currencies')->where('code', $company->currency)->value('exchange_rate'); 
    }

    public function isDueDatePassed()
    {
        $feeMaster = $this->feeMaster;
        
        if (!$feeMaster || !$feeMaster->due_date) {
            return false;
        }

        return now()->lte($feeMaster->due_date);
    }

    public function isPaid()
    {
        return $this->feePayments()->exists();
    }
}
