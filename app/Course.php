<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_event_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'modules' => 'object',
        'github_hook' => 'object',
        'last_event' => 'object',
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
