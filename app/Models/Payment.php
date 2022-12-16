<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    use HasFactory;

    const STATUS_SUCCEEDED = "Succeeded";

    protected $fillable = [
        'appointment_id',
        'user_id',
        'amount',
        'status',
        'method',
    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }
}
