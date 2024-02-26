<?php

use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\News\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\Users\UsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Auth section
 */
Route::prefix('auth')->group(function () {
    Route::post('request-register', [AuthController::class, 'requestRegister']);
    Route::post('register', [AuthController::class, 'register']);

    Route::post('request-login', [AuthController::class, 'requestLogin']);
    Route::post('login', [AuthController::class, 'login']);

    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

/**
 * Admin section
 */
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin|moderator'])->group(function () {
    Route::prefix('users')->middleware(['role:admin'])->group(function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::post('/', [UsersController::class, 'create']);

        Route::get('/{userId}', [UsersController::class, 'show']);
        Route::patch('/{userId}', [UsersController::class, 'update']);
        Route::delete('/{userId}', [UsersController::class, 'destroy']);
    });

    Route::prefix('news')->middleware(['role:admin|moderator'])->group(function () {
        Route::get('/', [AdminNewsController::class, 'index']);
        Route::post('/', [AdminNewsController::class, 'create']);

        Route::get('/{newsId}', [AdminNewsController::class, 'show']);
        Route::post('/{newsId}', [AdminNewsController::class, 'update']);
        Route::delete('/{newsId}', [AdminNewsController::class, 'destroy']);
    });

    Route::get('/app', [AppController::class, 'app']);
});

/**
 * Subscriber section
 */
Route::middleware(['auth:sanctum', 'role:viewer|moderator|admin'])->group(function () {
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{newsId}', [NewsController::class, 'show']);

    Route::get('/profile', [ProfileController::class, 'show']);
});
