<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_question_id',
        'content_answer_id',
        'answer',
    ];

    public function content_question()
    {
        return $this->belongsTo(ContentQuestion::class);
    }

    public function content_answer()
    {
        return $this->belongsTo(ContentAnswer::class);
    }
}
