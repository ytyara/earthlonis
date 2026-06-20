<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Place;

class Country extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'iso_code',
        'image',
    ];

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
