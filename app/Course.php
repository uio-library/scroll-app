<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $casts = [
    	'modules' => 'object',
    ];

    public $incrementing = true;
    protected $fillable = ['name', 'modules', 'id', 'header'];
}
