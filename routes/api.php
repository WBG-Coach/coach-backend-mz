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

use App\Http\Controllers\ProfileController;
Route::controller(ProfileController::class)->group(function () {
    Route::post('/profiles/search', 'search');
    Route::post('/profiles', 'save');
    Route::put('/profiles', 'update');
    Route::delete('/profiles/{id}', 'delete');
});

use App\Http\Controllers\PermissionController;
Route::controller(PermissionController::class)->group(function () {
    Route::post('/permissions/search', 'search');
    Route::post('/permissions', 'save');
    Route::put('/permissions', 'update');
    Route::delete('/permissions/{id}', 'delete');
});

use App\Http\Controllers\UserController;
Route::controller(UserController::class)->group(function () {
    Route::post('/users/search', 'search');
    Route::post('/users', 'save');
    Route::put('/users', 'update');
    Route::delete('/users/{id}', 'delete');
    Route::post('/users/lastAnswers', 'lastAnswers');
});

use App\Http\Controllers\SchoolController;
Route::controller(SchoolController::class)->group(function () {
    Route::post('/schools/search', 'search');
    Route::post('/schools', 'save');
    Route::put('/schools', 'update');
    Route::delete('/schools/{id}', 'delete');
});

use App\Http\Controllers\MatrixController;
Route::controller(MatrixController::class)->group(function () {
    Route::post('/matrixes/search', 'search');
    Route::post('/matrixes', 'save');
    Route::put('/matrixes', 'update');
    Route::delete('/matrixes/{id}', 'delete');
});

use App\Http\Controllers\CompetenceController;
Route::controller(CompetenceController::class)->group(function () {
    Route::post('/competencies/search', 'search');
    Route::post('/competencies', 'save');
    Route::put('/competencies', 'update');
    Route::delete('/competencies/{id}', 'delete');
});

use App\Http\Controllers\QuestionnaireController;
Route::controller(QuestionnaireController::class)->group(function () {
    Route::post('/questionnaires/search', 'search');
    Route::post('/questionnaires', 'save');
    Route::put('/questionnaires', 'update');
    Route::delete('/questionnaires/{id}', 'delete');
});

use App\Http\Controllers\QuestionController;
Route::controller(QuestionController::class)->group(function () {
    Route::post('/questions/search', 'search');
    Route::post('/questions', 'save');
    Route::put('/questions', 'update');
    Route::delete('/questions/{id}', 'delete');
});

use App\Http\Controllers\QuestionnaireQuestionsController;
Route::controller(QuestionnaireQuestionsController::class)->group(function () {
    Route::post('/questionnaire-questions/search', 'search');
    Route::post('/questionnaire-questions', 'save');
    Route::put('/questionnaire-questions', 'update');
    Route::delete('/questionnaire-questions/{id}', 'delete');
});

use App\Http\Controllers\QuestionnaireApplicationController;
Route::controller(QuestionnaireApplicationController::class)->group(function () {
    Route::post('/questionnaire-applications/search', 'search');
    Route::post('/questionnaire-applications', 'save');
    Route::put('/questionnaire-applications', 'update');
    Route::delete('/questionnaire-applications/{id}', 'delete');
});

use App\Http\Controllers\CoachController;
Route::controller(CoachController::class)->group(function () {
    Route::post('/coaches/questionnaire-applications/schools', 'questionnaireSchools');
    Route::post('/coaches/questionnaire-applications/teachers', 'questionnaireTeachers');
});

use App\Http\Controllers\OptionController;
Route::controller(OptionController::class)->group(function () {
    Route::post('/options/search', 'search');
    Route::post('/options', 'save');
    Route::put('/options', 'update');
    Route::delete('/options/{id}', 'delete');
});

use App\Http\Controllers\AnswerController;
Route::controller(AnswerController::class)->group(function () {
    Route::post('/answers/search', 'search');
    Route::post('/answers', 'save');
});

use App\Http\Controllers\NoteController;
Route::controller(NoteController::class)->group(function () {
    Route::post('/notes/search', 'search');
    Route::post('/notes', 'save');
    Route::put('/notes', 'update');
    Route::delete('/notes/{id}', 'delete');
});

use App\Http\Controllers\FeedbackController;
Route::controller(FeedbackController::class)->group(function () {
    Route::post('/feedbacks/search', 'search');
    Route::post('/feedbacks', 'save');
    Route::put('/feedbacks', 'update');
    Route::delete('/feedbacks/{id}', 'delete');
});