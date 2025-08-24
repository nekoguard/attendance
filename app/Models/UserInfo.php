<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_infos';
    protected $fillable = [
        'user_id',
        'paid_leave_remaining',
        'last_name',
        'first_name',
        'department_id',
        'section_id',
        'position_id',
        'work_type_id',
        'status_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'work_type_id');
    }
}
