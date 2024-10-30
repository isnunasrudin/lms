<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_grade')->withPivot('answer');
    }
}
