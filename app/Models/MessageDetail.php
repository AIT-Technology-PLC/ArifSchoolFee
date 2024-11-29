<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TouchParentUserstamp;

class MessageDetail extends Model
{
    use TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function parentModel()
    {
        return $this->message;
    }
}
