<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\InvitationController;
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
    Route::post('/signout', [AuthController::class, 'logout']);
    Route::apiResource('/board', BoardController::class);
    Route::apiResource('/list', TaskListController::class);
    Route::get('/list-board/{id}', [TaskListController::class, 'taskListOnBoard']);
    Route::post('/update-list-order', [TaskListController::class, 'updateListOrder']);
    Route::post('/update-card-order', [TaskListController::class, 'updateCardOrder']);
    Route::apiResource('/card',CardController::class);
    Route::post('/invite-member',[InvitationController::class, 'invite']);
    Route::post('/invite-accept',[InvitationController::class, 'accept']);
    Route::get('/invitation',[InvitationController::class, 'getUserInvitations']);
    // Route::get('/invitations/accept/{invitation}', [InvitationController::class, 'accept'])->name('invitations.accept');
});

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/signin', [AuthController::class, 'login']);
