<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
