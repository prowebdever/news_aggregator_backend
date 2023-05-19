<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Define a command to display an inspiring quote
Artisan::command('inspire', function () {
    // Get a random inspiring quote
    $quote = Inspiring::quote();

    // Display the quote as a comment
    $this->comment($quote);
})->purpose('Display an inspiring quote');
