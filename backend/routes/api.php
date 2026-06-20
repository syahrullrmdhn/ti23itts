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
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\PostController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}/public', [StudentController::class, 'publicShow']);
Route::post('/students/{id}/messages', [StudentController::class, 'storeWallMessage']);
Route::put('/students/{studentId}/messages/{messageId}', [StudentController::class, 'updateWallMessage']);
Route::delete('/students/{studentId}/messages/{messageId}', [StudentController::class, 'destroyWallMessage']);
Route::post('/students/{id}/vote', [StudentController::class, 'vote']);
Route::get('/episodes', [EpisodeController::class, 'index']);
Route::get('/episodes/{id}/public', [EpisodeController::class, 'publicShow']);
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts/{slug}/view', [PostController::class, 'recordView']);
Route::get('/posts/{slug}', [PostController::class, 'showBySlug']);
Route::post('/episodes/{id}/like', [EpisodeController::class, 'like']);
Route::post('/episodes/{id}/comment', [EpisodeController::class, 'comment']);
Route::put('/episodes/{episodeId}/comment/{commentId}', [EpisodeController::class, 'updateComment']);
Route::delete('/episodes/{episodeId}/comment/{commentId}', [EpisodeController::class, 'destroyComment']);
Route::get('/timeline', [TimelineController::class, 'index']);
Route::get('/hero-photos', [HeroPhotoController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('users', UserController::class);
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::get('/admin/students', [StudentController::class, 'adminIndex']);
    Route::get('/admin/episodes', [EpisodeController::class, 'adminIndex']);
    Route::get('/admin/posts', [PostController::class, 'adminIndex']);
    Route::get('/admin/posts/{post}', [PostController::class, 'show']);

    Route::apiResource('students', StudentController::class)->except(['index']);
    Route::apiResource('episodes', EpisodeController::class)->except(['index']);
    Route::apiResource('posts', PostController::class)->except(['index', 'show']);
    Route::apiResource('semesters', SemesterController::class);
    Route::post('/hero-photos', [HeroPhotoController::class, 'store']);
    Route::delete('/hero-photos/{id}', [HeroPhotoController::class, 'destroy']);
    
    Route::post('/timeline', [TimelineController::class, 'store']);
    Route::put('/timeline/{id}', [TimelineController::class, 'update']);
});
