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
        'last_commit_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'header' => 'object',
        'footer' => 'object',
        'modules' => 'object',
        'options' => 'object',

        'github_hook' => 'object',
        'last_event' => 'object',
    ];

    protected $fillable = ['name', 'modules', 'header', 'footer', 'repo', 'domain'];

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

    /**
     * Get a course option value.
     */
    public function option($key, $default = null)
    {
        return isset($this->options->$key) ? $this->options->$key : $default ;
    }
}
