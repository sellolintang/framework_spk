<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ElectionPeriodController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\CriterionController;
use App\Http\Controllers\Api\JuryCriterionController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\ArasResultController;
use App\Http\Controllers\Api\JuryController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\JuryDashboardController;
use App\Http\Controllers\Api\JuryScoringController;


Route::post('/login', [AuthController::class, 'login']);

Route::post('/candidates/register', [CandidateController::class, 'register']);

Route::get('/public/results', [AnnouncementController::class, 'publicResults']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);


    Route::apiResource('periods', ElectionPeriodController::class);


    Route::apiResource('candidates', CandidateController::class)
        ->except(['store']);


    Route::patch('/candidates/{candidate}/validate', [CandidateController::class, 'validateCandidate']);
    Route::patch('/candidates/{candidate}/reject', [CandidateController::class, 'rejectCandidate']);


    Route::post('/criteria/sync', [CriterionController::class, 'sync']);
    Route::apiResource('criteria', CriterionController::class);


    Route::get('/juries/options', [JuryController::class, 'options']);
    Route::post('/juries/{jury}/reset-password', [JuryController::class, 'resetPassword']);
    Route::patch('/juries/{jury}/toggle-status', [JuryController::class, 'toggleStatus']);
    Route::apiResource('juries', JuryController::class);


    Route::get('/jury-criteria/options', [JuryCriterionController::class, 'options']);
    Route::post('/jury-criteria/sync', [JuryCriterionController::class, 'sync']);
    Route::apiResource('jury-criteria', JuryCriterionController::class);


    Route::post('/interviews/generate', [InterviewController::class, 'generate']);
    Route::post('/interviews/reset', [InterviewController::class, 'reset']);
    Route::apiResource('interviews', InterviewController::class);


    Route::get('/my-scores', [ScoreController::class, 'myScores']);
    Route::apiResource('scores', ScoreController::class);


    Route::post('/aras-results/calculate', [ArasResultController::class, 'calculate']);
    Route::apiResource('aras-results', ArasResultController::class)
        ->only(['index', 'show', 'destroy']);

        
    Route::get('/monitoring/scores', [MonitoringController::class, 'scores']);


    Route::post('/announcements/check-readiness', [AnnouncementController::class, 'checkReadiness']);
    Route::post('/announcements/publish', [AnnouncementController::class, 'publish']);
    Route::post('/announcements/unpublish', [AnnouncementController::class, 'unpublish']);



    Route::get('/jury/dashboard-summary', [JuryDashboardController::class, 'summary']);

    Route::get('/jury/scoring-candidates', [JuryScoringController::class, 'index']);
    Route::get('/jury/scoring-candidates/{candidate}', [JuryScoringController::class, 'show']);
    Route::post('/jury/scoring-candidates/{candidate}/scores', [JuryScoringController::class, 'saveScores']);

    Route::get('/jury/scoring-history', [JuryScoringController::class, 'history']);
    Route::get('/jury/scoring-history/{candidate}', [JuryScoringController::class, 'historyDetail']);
});