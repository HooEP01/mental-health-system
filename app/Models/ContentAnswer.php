<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentAnswer extends Model
{
    use HasFactory;

    const STATUS_ANSWERED = 'Answered';
    const STATUS_COMMENTED = 'Commented';
    const STATUS_HIDDEN = 'Hidden';
    const STATUS_REPORT_HIDDEN = 'Report Hidden';
    const STATUS_REPORT_VISIBLE = 'Report Visible';

    protected $fillable = [
        'appointment_id',
        'task_id',
        'user_id',
        'content_id',
        'status',
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

    public function content()
    {
        return $this->hasOne(Content::class);
    }
}
