<?php

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // This will work and will log 'Hello World' in the log file
    Bus::chain([
        Bus::batch([
            new \App\Jobs\HelloWorld(),
        ])
    ])->dispatch();

    // Batcher will append the job to the batch, and this will not work
    Bus::chain([
        new \App\Jobs\Batcher(),
    ])->dispatch();
});
