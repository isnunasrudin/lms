<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }
}
