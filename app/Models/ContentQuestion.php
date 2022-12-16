<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentQuestion extends Model
{
    use HasFactory;
    const INDEX = 0;

    protected $fillable = [
        'content_id',
        'index',
        'category',
        'question',
        'description',
        'data',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

}
