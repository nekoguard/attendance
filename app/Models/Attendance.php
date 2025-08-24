<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'clock_in_at',
        'clock_out_at',
        'break_start_at',
        'break_end_at',
        'status_id',
        'working_time',
        'overtime_minutes',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
