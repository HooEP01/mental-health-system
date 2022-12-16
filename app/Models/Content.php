<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Content extends Model
{
    use HasFactory, HasSlug;

    const TYPE = ['text', 'textarea', 'select', 'radio', 'checkbox'];
    const CATEGORIES = ['Article', 'Case Study', 'Survey', 'Journal', 'Report'];
    const FORMATS = ['Basic', 'Unit'];

    const STATUSES = ['Draft','Complete'];
    const STATUS_APPROVE = 'Approve';
    const STATUS_DISAPPROVE = 'Disapprove';
    const STATUS_COMPLETE = 'Complete';
    const STATUS_DRAFT = 'Draft';

    const PROFESSIONAL_CATEGORY = 'Report';

    protected $fillable = [
        'user_id', 
        'title', 
        'description', 
        'category',
        'status', 
        'image', 
        'audio',
        'format_category',
        'formats',
        'slug', 
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function questions()
    {
        return $this->hasMany(ContentQuestion::class);
    }

    public function answers()
    {
        return $this->hasMany(ContentAnswer::class);
    }
}
