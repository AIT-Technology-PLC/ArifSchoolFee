<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compensation extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $table = 'compensations';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function isEarning()
    {
        return $this->type == 'earning';
    }

    public function isTaxable()
    {
        return $this->is_taxable == 1;
    }

    public function isAdjustable()
    {
        return $this->is_adjustable == 1;
    }

    public function canBeInputtedManually()
    {
        return $this->can_be_inputted_manually == 1;
    }
}