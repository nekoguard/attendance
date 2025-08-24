<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = [
        'department_id',
        'code',
        'name',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function userInfos()
    {
        return $this->hasMany(UserInfo::class);
    }

}
