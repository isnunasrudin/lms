<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use SoftDeletes;
    
    public $casts = [
        'required_token' => 'boolean'
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function($model){
            if($model->required_token && !$model->token)
                $model->token = strtoupper(Str::random(6));

            elseif(!$model->required_token && $model->token)
                $model->token = null;

        });

        static::updating(function($model){
            if($model->required_token && !$model->token)
                $model->token = strtoupper(Str::random(6));

            elseif(!$model->required_token && $model->token)
                $model->token = null;
        });
    }
}
