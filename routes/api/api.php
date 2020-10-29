<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return response([
        'message' => 'Laravel scaffold'
    ], 200);
});

Route::post('test-auth/{userID}', function () {
    return response([
        'message' => 'Laravel scaffold',
        'user-auth' => request()->auth
    ], 200);
})->middleware('auth.jwt');
