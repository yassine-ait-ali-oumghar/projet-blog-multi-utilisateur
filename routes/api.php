<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public-posts', [PostController::class, 'index']); // For the public layout to read posts!
Route::post('/assistant', [AssistantController::class, 'ask']);

// Protected Admin Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::post('/posts/{postId}/like', [PostController::class, 'toggleLike']);
    Route::post('/posts/{postId}/repost', [PostController::class, 'toggleRepost']);
    Route::post('/users/{userId}/follow', [FollowController::class, 'toggle']);
    Route::get('/notifications', [NotificationController::class, 'index']);

    // Comments routes (authenticated)
    Route::post('/posts/{postId}/comments', [\App\Http\Controllers\CommentController::class, 'store']);
    Route::delete('/posts/{postId}/comments/{commentId}', [\App\Http\Controllers\CommentController::class, 'destroy']);
});

// Comments index (public)
Route::get('/posts/{postId}/comments', [\App\Http\Controllers\CommentController::class, 'index']);

