<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'nim',
        'email',
        'phone',
        'department',
        'faculty',
        'qr_token',
        'attendance_status',
        'qr_image'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
