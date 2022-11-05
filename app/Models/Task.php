<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'appointment_id',
        'content_id',
        'title', 
        'description',
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function content(){
        return $this->belongsTo(Content::class);
    }
}
