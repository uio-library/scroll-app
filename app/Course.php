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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Get the exercises for this course.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

}
