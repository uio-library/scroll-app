<?php

namespace App\Observers;

use App\AnalyticsEvent;
use Carbon\Carbon;

class AnalyticsEventObserver
{
    public function creating(AnalyticsEvent $evt)
    {
        // Append timestamp and session id
        $evt->timestamp = Carbon::now();
        $evt->session_id = sha1(\Request::session()->getId());
    }
}
