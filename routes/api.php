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

use App\Http\Controllers\LoginController; 
Route::post('/auth', [LoginController::class, 'getToken']);
Route::post('/authAdmin', [LoginController::class, 'getTokenAdmin']);

use App\Http\Controllers\ProjectController;
Route::controller(ProjectController::class)->group(function () {
    Route::post('/projects/search', 'search');
    Route::middleware('auth:api')->post('/projects', 'save');
    Route::middleware('auth:api')->put('/projects', 'update');
    Route::middleware('auth:api')->delete('/projects/{id}', 'delete');
});

use App\Http\Controllers\ProfileController;
Route::controller(ProfileController::class)->group(function () {
    Route::middleware('auth:api')->post('/profiles/search', 'search');
    Route::middleware('auth:api')->post('/profiles', 'save');
    Route::middleware('auth:api')->put('/profiles', 'update');
    Route::middleware('auth:api')->delete('/profiles/{id}', 'delete');
});

use App\Http\Controllers\PermissionController;
Route::controller(PermissionController::class)->group(function () {
    Route::middleware('auth:api')->post('/permissions/search', 'search');
    Route::middleware('auth:api')->post('/permissions', 'save');
    Route::middleware('auth:api')->put('/permissions', 'update');
    Route::middleware('auth:api')->delete('/permissions/{id}', 'delete');
});

use App\Http\Controllers\UserController;
Route::controller(UserController::class)->group(function () {
    Route::middleware('auth:api')->post('/users/search', 'search');
    Route::middleware('auth:api')->post('/users', 'save');
    Route::middleware('auth:api')->put('/users', 'update');
    Route::middleware('auth:api')->delete('/users/{id}', 'delete');
    Route::middleware('auth:api')->post('/users/lastAnswers', 'lastAnswers');
    Route::middleware('auth:api')->post('/users/lastFeedbacks', 'lastFeedbacks');
    Route::post('/createCoach', 'createCoach');
    Route::post('/changePassword', 'changePassword');
    Route::middleware('auth:api')->post('/createTeacher', 'createTeacher');
});

use App\Http\Controllers\SchoolController;
Route::controller(SchoolController::class)->group(function () {
    Route::middleware('auth:api')->post('/schools/search', 'search');
    Route::middleware('auth:api')->post('/schools', 'save');
    Route::middleware('auth:api')->put('/schools', 'update');
    Route::middleware('auth:api')->delete('/schools/{id}', 'delete');
});

use App\Http\Controllers\MatrixController;
Route::controller(MatrixController::class)->group(function () {
    Route::middleware('auth:api')->post('/matrixes/search', 'search');
    Route::middleware('auth:api')->post('/matrixes', 'save');
    Route::middleware('auth:api')->put('/matrixes', 'update');
    Route::middleware('auth:api')->delete('/matrixes/{id}', 'delete');
});

use App\Http\Controllers\CompetenceController;
Route::controller(CompetenceController::class)->group(function () {
    Route::middleware('auth:api')->post('/competencies/search', 'search');
    Route::middleware('auth:api')->post('/competencies', 'save');
    Route::middleware('auth:api')->put('/competencies', 'update');
    Route::middleware('auth:api')->delete('/competencies/{id}', 'delete');
});

use App\Http\Controllers\QuestionnaireController;
Route::controller(QuestionnaireController::class)->group(function () {
    Route::middleware('auth:api')->post('/questionnaires/search', 'search');
    Route::middleware('auth:api')->post('/questionnaires', 'save');
    Route::middleware('auth:api')->put('/questionnaires', 'update');
    Route::middleware('auth:api')->delete('/questionnaires/{id}', 'delete');
});

use App\Http\Controllers\QuestionController;
Route::controller(QuestionController::class)->group(function () {
    Route::middleware('auth:api')->post('/questions/search', 'search');
    Route::middleware('auth:api')->post('/questions', 'save');
    Route::middleware('auth:api')->put('/questions', 'update');
    Route::middleware('auth:api')->delete('/questions/{id}', 'delete');
});

use App\Http\Controllers\QuestionnaireQuestionsController;
Route::controller(QuestionnaireQuestionsController::class)->group(function () {
    Route::middleware('auth:api')->post('/questionnaire-questions/search', 'search');
    Route::middleware('auth:api')->post('/questionnaire-questions', 'save');
    Route::middleware('auth:api')->put('/questionnaire-questions', 'update');
    Route::middleware('auth:api')->delete('/questionnaire-questions/{id}', 'delete');
});

use App\Http\Controllers\QuestionnaireApplicationController;
Route::controller(QuestionnaireApplicationController::class)->group(function () {
    Route::middleware('auth:api')->post('/questionnaire-applications/search', 'search');
    Route::middleware('auth:api')->post('/questionnaire-applications', 'save');
    Route::middleware('auth:api')->put('/questionnaire-applications', 'update');
    Route::middleware('auth:api')->delete('/questionnaire-applications/{id}', 'delete');
});

use App\Http\Controllers\CoachController;
Route::controller(CoachController::class)->group(function () {
    Route::middleware('auth:api')->post('/coaches/questionnaire-applications/schools', 'questionnaireSchools');
});

use App\Http\Controllers\OptionController;
Route::controller(OptionController::class)->group(function () {
    Route::middleware('auth:api')->post('/options/search', 'search');
    Route::middleware('auth:api')->post('/options', 'save');
    Route::middleware('auth:api')->put('/options', 'update');
    Route::middleware('auth:api')->delete('/options/{id}', 'delete');
});

use App\Http\Controllers\AnswerController;
Route::controller(AnswerController::class)->group(function () {
    Route::middleware('auth:api')->post('/answers/search', 'search');
    Route::middleware('auth:api')->post('/answers', 'save');
});

use App\Http\Controllers\NoteController;
Route::controller(NoteController::class)->group(function () {
    Route::middleware('auth:api')->post('/notes/search', 'search');
    Route::middleware('auth:api')->post('/notes', 'save');
    Route::middleware('auth:api')->put('/notes', 'update');
    Route::middleware('auth:api')->delete('/notes/{id}', 'delete');
});

use App\Http\Controllers\FeedbackController;
Route::controller(FeedbackController::class)->group(function () {
    Route::middleware('auth:api')->post('/feedbacks/search', 'search');
    Route::middleware('auth:api')->post('/feedbacks', 'save');
    Route::middleware('auth:api')->put('/feedbacks', 'update');
    Route::middleware('auth:api')->delete('/feedbacks/{id}', 'delete');
});

use App\Http\Controllers\ContentGuideController;
Route::controller(ContentGuideController::class)->group(function () {
    Route::middleware('auth:api')->post('/content-guides/search', 'search');
});

use App\Http\Controllers\ReportController;
Route::controller(ReportController::class)->group(function () {
    Route::middleware('auth:api')->post('/reports/competence-evolutions', 'competenceEvolutions');
    Route::middleware('auth:api')->post('/reports/competences', 'competences');
    Route::middleware('auth:api')->post('/reports/dashboard', 'dashboard');
    Route::middleware('auth:api')->post('/reports/sessions-by-school', 'sessionsBySchool');
    Route::middleware('auth:api')->post('/reports/sessions-by-coach', 'sessionsByCoach');
    Route::middleware('auth:api')->post('/reports/sessions-by-teacher', 'sessionsByTeacher');
    Route::middleware('auth:api')->post('/reports/competences-by-school', 'competencesBySchool');
    Route::middleware('auth:api')->post('/reports/sessions-by-year', 'sessionsByYear');
    Route::middleware('auth:api')->post('/reports/sessions-qty-by-project', 'sessionsQtyByProject');
    Route::middleware('auth:api')->post('/reports/teacher-competences', 'teacherCompetences');
    Route::middleware('auth:api')->post('/reports/answers-by-city', 'answersByCity');
});

use App\Http\Controllers\DocumentQuestionnaireController;
Route::controller(DocumentQuestionnaireController::class)->group(function () {
    Route::middleware('auth:api')->post('/document-questionnaire', 'save');
});