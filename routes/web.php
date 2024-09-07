<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

Route::prefix('supportchat')->group(function () {
//	Route::post('/index', [SupportChatController::class, 'index'])->name('supportchat.index');
});
