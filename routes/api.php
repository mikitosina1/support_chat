<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\Api\V1\User\SessionController;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

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

Route::prefix('v1/support-chat')
    ->middleware('auth:sanctum')
    ->name('api.v1.support-chat.')
    ->group(function () {
        Route::get('/session', SessionController::class)
            ->name('session.show');

        Route::get('/room', [SupportChatController::class, 'getOrCreateRoom'])
            ->name('room.show-or-create');

        Route::post('/rooms', [SupportChatController::class, 'createRoom'])
            ->name('rooms.store');

        Route::post('/rooms/{room}/join', [SupportChatController::class, 'joinRoom'])
            ->name('rooms.join');

        Route::post('/rooms/{room}/leave', [SupportChatController::class, 'leaveRoom'])
            ->name('rooms.leave');

        Route::get('/rooms/{room}/messages', [SupportChatController::class, 'getMessages'])
            ->name('rooms.messages.index');

        Route::post('/rooms/{room}/messages', [SupportChatController::class, 'sendMessage'])
            ->name('rooms.messages.store');

        Route::get('/translations', [SupportChatController::class, 'getTranslations'])
            ->name('translations.index');
    });

Route::prefix('v1/admin/support-chat')
    ->middleware(['auth:sanctum', 'is_admin'])
    ->name('api.v1.admin.support-chat.')
    ->group(function () {
        Route::get('/rooms/{room}/messages', [SupportChatController::class, 'getMessages'])
            ->name('rooms.messages.index');

        Route::post('/rooms/{room}/messages', [SupportChatController::class, 'sendMessage'])
            ->name('rooms.messages.store');
    });
