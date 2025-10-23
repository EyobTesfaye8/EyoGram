<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// auth routes
Route::group(['prefix'=>'auth'],function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:api'])->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/delete_account', [AuthController::class, 'delete_account']);
});
Route::middleware(['auth:api', 'role:user'])->group(function(){
    // post routes
    Route::get('/post/{id}', [PostController::class, 'get_post']);
    Route::post('/post', [PostController::class, 'create_post']);
    Route::put('/post/{id}', [PostController::class, 'update_post']);
    Route::delete('/post/{id}', [PostController::class, 'delete_post']);
    // comment routes
    Route::apiResource('/comment', CommentController::class);
});
