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
        'faculty',
        'department',
        'qr_token',
        'qr_image',
        'certificate_file',
        'attendance_status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
