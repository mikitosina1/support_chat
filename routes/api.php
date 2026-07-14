<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\Api\V1\Admin\MessageController as AdminMessageController;
use Modules\SupportChat\App\Http\Controllers\Api\V1\User\MessageController;
use Modules\SupportChat\App\Http\Controllers\Api\V1\User\RoomController;
use Modules\SupportChat\App\Http\Controllers\Api\V1\User\SessionController;
use Modules\SupportChat\App\Http\Controllers\Api\V1\User\TranslationController;

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

        Route::get('/room', [RoomController::class, 'getOrCreate'])
            ->name('room.show-or-create');

        Route::post('/rooms', [RoomController::class, 'store'])
            ->name('rooms.store');

        Route::post('/rooms/{room}/join', [RoomController::class, 'join'])
            ->name('rooms.join');

        Route::post('/rooms/{room}/leave', [RoomController::class, 'leave'])
            ->name('rooms.leave');

        Route::get('/rooms/{room}/messages', [MessageController::class, 'index'])
            ->name('rooms.messages.index');

        Route::post('/rooms/{room}/messages', [MessageController::class, 'store'])
            ->name('rooms.messages.store');

        Route::get('/translations', [TranslationController::class, 'getTranslations'])
            ->name('translations.index');
    });

Route::prefix('v1/admin/support-chat')
    ->middleware(['auth:sanctum', 'is_admin'])
    ->name('api.v1.admin.support-chat.')
    ->group(function () {
        Route::get('/rooms/{room}/messages', [AdminMessageController::class, 'index'])
            ->name('rooms.messages.index');

        Route::post('/rooms/{room}/messages', [AdminMessageController::class, 'store'])
            ->name('rooms.messages.store');
    });
