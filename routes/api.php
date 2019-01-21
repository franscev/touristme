<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('place', 'PlaceController');
Route::apiResource('user', 'UserController');
Route::apiResource('login', 'LoginController@login');