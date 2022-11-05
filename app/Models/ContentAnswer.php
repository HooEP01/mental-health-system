<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'task_id',
        'user_id',
        'status',
        'start_datetime',
        'end_datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function task()
    {
        return $this->belongTo(Task::class);
    }

    public function user()
    {
        return $this->belongTo(User::class);
    }
}
