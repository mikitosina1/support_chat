<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\SupportChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('supportchat')->group(function () {
	Route::post('/index', [SupportChatController::class, 'index'])->name('supportchat.index');
});

Route::get('/{any}', [SupportChatController::class, 'index'])->where('any', '.*');
