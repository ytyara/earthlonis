<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'country_id',
        'category_id',
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'description',
        'quick_facts',
        'know_before_you_go',
        'sources',
        'tagline',
        'latitude',
        'longitude',
        'image',
        'is_published',
    ];

    protected $casts = [
        'quick_facts' => 'array',
        'know_before_you_go' => 'array',
        'sources' => 'array',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(PlaceImage::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with('replies')
            ->latest();
    }
}