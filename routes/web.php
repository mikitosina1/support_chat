<?php

require ('auth.php');

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

Route::prefix('support-chat')->name('supportchat.')->group(function () {
	Route::post('/room', [SupportChatController::class, 'createRoom'])->name('create.room');
	Route::post('/room/{room}/message', [SupportChatController::class, 'sendMessage'])->name('send.message');
});
