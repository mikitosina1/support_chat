<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\ChatController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
	Route::get('supportchat', fn(Request $request) => $request->user())->name('supportchat');
	Route::post('/chat/rooms', [ChatController::class, 'createRoom']);
	Route::post('/chat/rooms/{room}/join', [ChatController::class, 'joinRoom']);
	Route::post('/chat/rooms/{room}/leave', [ChatController::class, 'leaveRoom']);
	Route::post('/chat/rooms/{room}/messages', [ChatController::class, 'sendMessage']);
	Route::get('/chat/rooms/{room}/messages', [ChatController::class, 'getMessages']);
});
