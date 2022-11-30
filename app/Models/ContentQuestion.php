<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'index',
        'category',
        'question',
        'description',
        'data',
        'image',
        'audio',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

}
