<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('place', 'PlaceController');
Route::apiResource('user', 'UserController');
Route::apiResource('role', 'RoleController');

Route::post('login', 'LoginController@login');
Route::post('login/web', 'LoginController@loginWeb');


