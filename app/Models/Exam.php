<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;
    
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
