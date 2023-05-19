<?php

use Illuminate\Support\Facades\Route;

// Define a route for the home page
Route::get('/', function () {
    // Return the welcome view
    return view('welcome');
});
