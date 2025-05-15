<?php

require('auth.php');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

Route::prefix('support-chat')->name('supportchat.')->group(function () {
	Route::post('/room', [SupportChatController::class, 'createRoom'])->name('create.room');
	Route::post('/room/{room}/message', [SupportChatController::class, 'sendMessage'])->name('send.message');
});

Route::get('/get-user-info', function (Request $request) {
	return response()->json([
		'user_id' => session('chat_token')['user_id'],
		'user_name' => session('chat_token')['user_name'],
		'user_lastname' => session('chat_token')['user_lastname'],
		'token' => session('chat_token')['token'],
		'r' => session('role')
	]);
});

Route::middleware(['web', 'auth'])
	->prefix('admin/support-chat')
	->name('admin.supportchat.')
	->group(function () {
		Route::get('/', [SupportChatController::class, 'index'])->name('index');
	});
