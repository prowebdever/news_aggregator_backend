<?php

/*
* API ROUTES    api/
*/


/* Public routes */
Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

/* News & Filters */
Route::get('/news', [App\Http\Controllers\Api\NewsController::class, 'index']);
Route::get('/news/filters', [App\Http\Controllers\Api\NewsController::class, 'getFilters']);

/* Authenticated Routes */
Route::group(['middleware' => 'auth:sanctum'], function() {
    // Logout api
    Route::get('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
});
