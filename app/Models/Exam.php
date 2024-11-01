<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy("order_column");
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
