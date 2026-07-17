<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\Api\V1\Admin\AdminRoomController;
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
        Route::get('/session', SessionController::class)->name('session.show');

        Route::get('/rooms/current', [RoomController::class, 'current'])
            ->name('rooms.current');

        Route::post('/rooms', [RoomController::class, 'store'])
            ->name('rooms.store');

        Route::patch('/rooms/{room}/resolve', [RoomController::class, 'resolve'])
            ->name('rooms.resolve');

        Route::get('/rooms/{room}/messages', [MessageController::class, 'index'])
            ->name('rooms.messages.index');

        Route::post('/rooms/{room}/messages', [MessageController::class, 'store'])
            ->name('rooms.messages.store');

        Route::patch('/rooms/{room}/messages/read', [MessageController::class, 'markRead'])
            ->name('rooms.messages.read');

        Route::get('/translations', TranslationController::class)
            ->name('translations.index');
    });

Route::prefix('v1/admin/support-chat')
    ->middleware(['auth:sanctum', 'is_admin'])
    ->name('api.v1.admin.support-chat.')
    ->group(function () {
        Route::get('/rooms', [AdminRoomController::class, 'index'])
            ->name('rooms.index');

        Route::get('/rooms/{room}', [AdminRoomController::class, 'show'])
            ->name('rooms.show');

        Route::patch('/rooms/{room}/close', [AdminRoomController::class, 'close'])
            ->name('rooms.close');

        Route::get('/rooms/{room}/messages', [AdminMessageController::class, 'index'])
            ->name('rooms.messages.index');

        Route::post('/rooms/{room}/messages', [AdminMessageController::class, 'store'])
            ->name('rooms.messages.store');
    });
