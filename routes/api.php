<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/schools/search', ['as' => 'schools.search', 'uses' => 'SchoolController@search']);
Route::post('/schools', ['as' => 'schools.save', 'uses' => 'SchoolController@save']);
Route::put('/schools', ['as' => 'schools.update', 'uses' => 'SchoolController@update']);
Route::delete('/schools/{id}', ['as' => 'schools.delete', 'uses' => 'SchoolController@delete']);

Route::post('/profiles/search', ['as' => 'profiles.search', 'uses' => 'ProfileController@search']);
Route::post('/profiles', ['as' => 'profiles.save', 'uses' => 'ProfileController@save']);
Route::put('/profiles', ['as' => 'profiles.update', 'uses' => 'ProfileController@update']);
Route::delete('/profiles/{id}', ['as' => 'profiles.delete', 'uses' => 'ProfileController@delete']);

Route::post('/users/search', ['as' => 'users.search', 'uses' => 'UserController@search']);
Route::post('/users', ['as' => 'users.save', 'uses' => 'UserController@save']);
Route::put('/users', ['as' => 'users.update', 'uses' => 'UserController@update']);
Route::delete('/users/{id}', ['as' => 'users.delete', 'uses' => 'UserController@delete']);



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
