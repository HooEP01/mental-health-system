<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    const STATUS_BOOKED = "Booked";
    const STATUS_PAID = "Paid";
    const STATUS_APPROVED = 'Approved';
    const STATUS_DISAPPROVED = 'Disapproved';
    
    protected $fillable = [
        'event_id',
        'user_id',
        'reason',
        'status',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
