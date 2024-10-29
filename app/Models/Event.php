<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
