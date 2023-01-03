<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    const STATUS_FAILED = 'Failed';
    const STATUS_DELETED = 'Deleted';
    const STATUS_EDITED = 'Edited';
    const STATUS_DELIVERED = 'Delivered';
    const STATUS_READ = 'Read';

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
