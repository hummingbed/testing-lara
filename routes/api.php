<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
 

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'getAllTask'])->name('tasks.getAllTask'); // Fetch all tasks
    Route::get('/{id}', [TaskController::class, 'getSingleTaskById']); // Fetch single task
    Route::post('/', [TaskController::class, 'createTask']); // Create task
    Route::put('/{id}', [TaskController::class, 'updateTask']);
    Route::delete('/{id}', [TaskController::class, 'deleteTask'])->name('tasks.deleteTask'); // Delete task
});