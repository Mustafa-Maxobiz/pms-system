<?php

use App\Http\Middleware\ActivityLogger;

return [
    'web' => [
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ActivityLogger::class,
    ],
];

