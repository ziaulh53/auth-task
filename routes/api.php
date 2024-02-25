<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\TaskListController;
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



Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('/board', BoardController::class);
    Route::apiResource('/list', TaskListController::class);
    Route::get('/list-board/{id}', [TaskListController::class, 'taskListOnBoard']);
    Route::apiResource('/card',CardController::class);
    // Route::get('/board/{id}', [BoardController::class, 'singleBoard']);
});

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/signin', [AuthController::class, 'login']);
