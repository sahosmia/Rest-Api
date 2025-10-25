<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\UserController;
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

Route::prefix('v1')->group(function () {
    // V1 API routes

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::get('/', function () {
        return 'this is home route';
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('categories/list', [CategoryController::class, 'list'])->name('categories.list');
        Route::apiResource('categories', CategoryController::class);

        Route::get('blogs/list', [BlogController::class, 'list'])->name('blogs.list');
        Route::apiResource('blogs', BlogController::class);

        Route::get('tags/list', [TagController::class, 'list'])->name('tags.list');
        Route::apiResource('tags', TagController::class);

        Route::get('users/list', [UserController::class, 'list'])->name('users.list');
        Route::apiResource('users', UserController::class);
        
        Route::apiResource('blogs.comments', CommentController::class)->shallow();
    });
});
