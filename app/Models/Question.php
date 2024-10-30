<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Question extends Model implements Sortable
{
    use SortableTrait;

    protected $casts = [
        'options' => 'array'
    ];
}
