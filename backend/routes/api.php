<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\Api\SemesterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HeroPhotoController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}/public', [StudentController::class, 'publicShow']);
Route::get('/episodes', [EpisodeController::class, 'index']);
Route::post('/episodes/{id}/like', [EpisodeController::class, 'like']);
Route::post('/episodes/{id}/comment', [EpisodeController::class, 'comment']);
Route::get('/timeline', [TimelineController::class, 'index']);
Route::get('/hero-photos', [HeroPhotoController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('users', UserController::class);
    Route::get('/admin/students', [StudentController::class, 'adminIndex']);

    Route::apiResource('students', StudentController::class)->except(['index']);
    Route::apiResource('episodes', EpisodeController::class)->except(['index']);
    Route::apiResource('semesters', SemesterController::class);
    Route::post('/hero-photos', [HeroPhotoController::class, 'store']);
    Route::delete('/hero-photos/{id}', [HeroPhotoController::class, 'destroy']);
    
    Route::post('/timeline', [TimelineController::class, 'store']);
    Route::put('/timeline/{id}', [TimelineController::class, 'update']);
});
