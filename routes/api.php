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

Route::post('/login', [AuthController::class, 'login']);

Route::post('/candidates/register', [CandidateController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('/periods', ElectionPeriodController::class);
    Route::apiResource('/candidates', CandidateController::class)->except(['store']);
    Route::apiResource('/criteria', CriterionController::class);
    Route::apiResource('/jury-criteria', JuryCriterionController::class);
    Route::apiResource('/interviews', InterviewController::class);
    Route::apiResource('/scores', ScoreController::class);
    Route::apiResource('/aras-results', ArasResultController::class);
});

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
