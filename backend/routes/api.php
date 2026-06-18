<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\Api\SemesterController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/students', [StudentController::class, 'index']);
Route::get('/episodes', [EpisodeController::class, 'index']);
Route::get('/timeline', [TimelineController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::apiResource('students', StudentController::class)->except(['index']);
    Route::apiResource('episodes', EpisodeController::class)->except(['index']);
    Route::apiResource('semesters', SemesterController::class);
    
    Route::post('/timeline', [TimelineController::class, 'store']);
    Route::put('/timeline/{id}', [TimelineController::class, 'update']);
});
