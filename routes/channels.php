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

// For private channels
/*
Broadcast::channel('private-channel-AB', function ($user) {
    return !is_null($user);
});
*/

// Presence channel
/*
Broadcast::channel('group-channel.{id}', function ($user, $id) {
    if (true) {
        return ['id' => $user->id, 'name' => $user->name];
    }

}); */

Broadcast::channel('message-sent.{sender}.{receiver}', function ($user) {
    return !is_null($user);
});