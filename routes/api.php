<?php

use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SharedTaskListController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('task-lists.tasks', TaskController::class)->shallow()->middleware('auth:sanctum');
Route::apiResource('taskslist', TaskListController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/shared-task-lists/share', [SharedTaskListController::class, 'share']);
    Route::get('/shared-task-lists', [SharedTaskListController::class, 'index']);
    Route::get('/shared-task-lists/{task_list_id}', [SharedTaskListController::class, 'show']);
    Route::get('/shared-task-lists/{task_list_id}/shared-users', [SharedTaskListController::class, 'sharedUsers']);
    Route::put('/shared-task-lists/{task_list_id}', [SharedTaskListController::class, 'update']);
    Route::delete('task-lists/{task_list_id}/revoke', [SharedTaskListController::class, 'revokeAccess']);
});


Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');

