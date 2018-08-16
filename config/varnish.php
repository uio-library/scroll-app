<?php

return [

    // Path to varnishadm
    'adm_path' => env('VARNISH_ADM_PATH', 'varnishadm'),

    // Domain to purge
    'domain' => env('VARNISH_DOMAIN', 'localhost')

];
