<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    protected $table = 'work_types';
    protected $fillable = [
        'code',
        'name',
    ];

    public function userInfos()
    {
        return $this->hasMany(UserInfo::class, 'work_type_id');
    }
}
