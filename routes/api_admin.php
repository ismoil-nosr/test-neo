<?php

/**
 * Admin section
 */

use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Admin\User\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin|moderator'])->group(function () {
    Route::prefix('users')->middleware(['role:admin'])->group(function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::post('/', [UsersController::class, 'create']);

        Route::get('/{userId}', [UsersController::class, 'show']);
        Route::patch('/{userId}', [UsersController::class, 'update']);
        Route::delete('/{userId}', [UsersController::class, 'destroy']);
    });

    Route::prefix('news')->middleware(['role:admin|moderator'])->group(function () {
        Route::get('/', [NewsController::class, 'index']);
        Route::post('/', [NewsController::class, 'create']);

        Route::get('/{newsId}', [NewsController::class, 'show']);
        Route::post('/{newsId}', [NewsController::class, 'update']);
        Route::delete('/{newsId}', [NewsController::class, 'destroy']);
    });

    Route::get('/app', [AppController::class, 'app']);
});
