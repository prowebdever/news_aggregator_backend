<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


// Define a channel for user with given id
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    // Check if the user id matches the given id
    $isUserMatched = (int) $user->id === (int) $id;

    // Return true if user id matches the given id, false otherwise
    return $isUserMatched;
});
