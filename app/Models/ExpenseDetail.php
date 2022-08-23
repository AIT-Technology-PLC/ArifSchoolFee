<?php

namespace App\Models;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseDetail extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}