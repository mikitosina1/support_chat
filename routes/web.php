<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

Route::prefix('supportchat')->group(function () {
	Route::get('/messages', [SupportChatController::class, 'messages'])->name('supportchat.messages');
	Route::post('/send', [SupportChatController::class, 'send'])->name('supportchat.send');
	Route::post('/create', [SupportChatController::class, 'create'])->name('supportchat.create');
});
