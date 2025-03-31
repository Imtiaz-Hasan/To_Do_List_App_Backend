<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload-profile-picture', [AuthController::class, 'uploadProfilePicture']);
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'name' => $request->user()->name,
            'image' => $request->user()->image
                ? asset('storage/' . $request->user()->image)
                : null,
        ]);
    });
    
    // Task routes
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete']);
});