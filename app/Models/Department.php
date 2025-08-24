<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = ['code', 'name'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    
    public function userInfos()
    {
        return $this->hasMany(UserInfo::class, 'department_id');
    }
}
