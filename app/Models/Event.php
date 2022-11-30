<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const CATEGORIES = ['Individual', 'Group', 'Seminar'];
    const CATEGORY_INDIVIDUAL = 'Individual';
    const CATEGORY_GROUP = 'Group';
    const CATEGORY_SEMINAR = 'Seminar';

    const ATTENDANCE_INDIVIDUAL = '1';

    const PRICE_FREE = 0;
    const PRICE_FREE_STRING = 'Free';

    const SESSION_LENGTH_SEMINAR = 0;

    const STATUSES = ['Draft', 'Complete'];
    const STATUS_APPROVE = 'Approve';
    const STATUS_DISAPPROVE = 'Disapprove';

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'attendance',
        'price',
        'image',
        'description',
        'status',
        'session_length',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
