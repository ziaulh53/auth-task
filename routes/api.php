<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\TaskListController;
use App\Http\Middleware\BoardAuthorization;
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
    
    //board api
    Route::middleware('board.auth')->get('/board/{id}', [BoardController::class,'show']);
    Route::apiResource('/board', BoardController::class);
    Route::post('/member-delete', [BoardController::class, 'removeMember']);

    // tasklist api
    Route::apiResource('/list', TaskListController::class);
    Route::middleware('board.auth')->get('/list-board/{id}', [TaskListController::class, 'taskListOnBoard']);
    Route::post('/update-list-order', [TaskListController::class, 'updateListOrder']);
    Route::post('/update-card-order', [TaskListController::class, 'updateCardOrder']);
    
    // card api
    Route::apiResource('/card',CardController::class);
    Route::post('/card/assign',[CardController::class,'assignMember']);
    Route::post('/card/assign-remove',[CardController::class,'removeMemberFromCard']);
    Route::post('card/labels', [CardController::class, 'updateCardLabel']);

    // invitation
    Route::post('/invite-member',[InvitationController::class, 'invite']);
    Route::post('/invite-accept',[InvitationController::class, 'accept']);
    Route::post('/invite-cancel',[InvitationController::class,'cancelInvitation']);
    Route::get('/invitation',[InvitationController::class, 'getUserInvitations']);
});

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/signin', [AuthController::class, 'login']);
Route::post('/forgot-password',[AuthController::class, 'resetPasswordRequest'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');