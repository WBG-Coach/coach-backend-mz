<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// URL::forceScheme('https');

Route::get('/', function () {
    return "Hello WB :)";
});

Route::post('/login', ['as' => 'login', 'uses' => 'LoginController@login']);
Route::post('/logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

Route::group(['middleware'=>'auth'], function(){
    //  
});
