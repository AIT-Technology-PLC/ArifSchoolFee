<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobExtra extends Model
{
    use TouchParentUserstamp, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by')->withDefault(['name' => 'N/A']);
    }

    public function parentModel()
    {
        return $this->job;
    }

    public function subtract()
    {
        $this->status = 'subtracted';

        $this->executed_by = auth()->id();

        $this->save();
    }

    public function add()
    {
        $this->status = 'added';

        $this->executed_by = auth()->id();

        $this->save();
    }

    public function isSubtracted()
    {
        return $this->status == 'subtracted';
    }

    public function isAdded()
    {
        return $this->status == 'added';
    }
}
