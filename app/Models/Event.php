<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'event_date',
        'event_time',
        'quota',
        'use_certificate',
        'banner',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
