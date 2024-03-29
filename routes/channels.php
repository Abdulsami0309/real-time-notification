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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('status-update', function ($user) {
    return $user;
});
Broadcast::channel('get-message', function ($user) {
    return $user;
});
Broadcast::channel('delete-single-message', function ($user) {
    return $user;
});
Broadcast::channel('update-message', function ($user) {
    return $user;
});
