<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $casts = [
    	'modules' => 'object',
    ];

    protected $fillable = ['name', 'modules', 'header', 'headertext', 'footer', 'repo'];

    /**
     * Get the exercises for this course.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

}
