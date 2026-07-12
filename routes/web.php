<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

Route::middleware(['auth', 'verified', 'is_admin'])
    ->prefix('admin/support-chat')
    ->name('admin.supportchat.')
    ->group(function () {
        Route::get('/', [SupportChatController::class, 'index'])->name('index');
        Route::get('/room/{room}', [SupportChatController::class, 'show'])->name('room.show');
        Route::get('/room/{room}/messages', [SupportChatController::class, 'getMessages'])
            ->name('room.messages.index');
        Route::post('/room/{room}/messages', [SupportChatController::class, 'sendMessage'])
            ->name('room.messages.store');
    });
