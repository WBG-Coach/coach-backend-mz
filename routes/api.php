<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

use App\Http\Controllers\SchoolController;
Route::controller(SchoolController::class)->group(function () {
    Route::post('/schools/search', 'search');
    Route::post('/schools', 'save');
    Route::put('/schools', 'update');
    Route::delete('/schools/{id}', 'delete');
});


use App\Http\Controllers\ProfileController;
Route::controller(ProfileController::class)->group(function () {
    Route::post('/profiles/search', 'search');
    Route::post('/profiles', 'save');
    Route::put('/profiles', 'update');
    Route::delete('/profiles/{id}', 'delete');
});

use App\Http\Controllers\UserController;
Route::controller(UserController::class)->group(function () {
    Route::post('/users/search', 'search');
    Route::post('/users', 'save');
    Route::put('/users', 'update');
    Route::delete('/users/{id}', 'delete');
});