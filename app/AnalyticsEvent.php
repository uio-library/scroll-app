<?php

namespace App;

use App\Events\AnalyticsEventCreating;
use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_type', 'timestamp', 'session_id', 'data'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'timestamp',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * Disable the default created_at and updated_at, since an event never changes.
     * We will use a single timestamp column instead.
     *
     * @var bool
     */
    public $timestamps = false;
}
