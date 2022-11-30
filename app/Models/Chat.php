<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 
        'user_id', 
        'message', 
        'status',
    ];

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
