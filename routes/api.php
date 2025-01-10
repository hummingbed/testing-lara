<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
 

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


//authenticated endpoints
//check the middleware directory for the rate-limit code
Route::middleware(['auth:api'])->group(function () {

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'getAllTask'])->name('tasks.getAllTask'); // Fetch all tasks
        Route::get('/{id}', [TaskController::class, 'getSingleTaskById']); // Fetch single task
        Route::post('/', [TaskController::class, 'createTask']); // Create task
        Route::put('/{id}', [TaskController::class, 'updateTask']);
        Route::delete('/{id}', [TaskController::class, 'deleteTask'])->name('tasks.deleteTask'); // Delete task
    });

    Route::post('/refresh', [AuthController::class, 'refresh']); // Refresh token
    Route::post('/logout', [AuthController::class, 'logout']); // Logout
});



