<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('answer', 'AnswerCrudController');
    Route::crud('answer-file', 'AnswerFileCrudController');
    Route::crud('competence', 'CompetenceCrudController');
    Route::crud('feedback', 'FeedbackCrudController');
    Route::crud('feedback-answer', 'FeedbackAnswerCrudController');
    Route::crud('matrix', 'MatrixCrudController');
    Route::crud('note', 'NoteCrudController');
    Route::crud('option', 'OptionCrudController');
    Route::crud('permission', 'PermissionCrudController');
    Route::crud('profile', 'ProfileCrudController');
    Route::crud('question', 'QuestionCrudController');
    Route::crud('questionnaire', 'QuestionnaireCrudController');
    Route::crud('questionnaire-application', 'QuestionnaireApplicationCrudController');
    Route::crud('questionnaire-audit', 'QuestionnaireAuditCrudController');
    Route::crud('questionnaire-question', 'QuestionnaireQuestionCrudController');
    Route::crud('school', 'SchoolCrudController');
    Route::crud('user-school', 'UserSchoolCrudController');
    Route::crud('content-guide', 'ContentGuideCrudController');
}); // this should be the absolute last line of this file