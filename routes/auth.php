<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportChat\App\Http\Controllers\Auth\AuthenticatedSessionController_Extension;

Route::post('/login', [AuthenticatedSessionController_Extension::class, 'store'])->name('login');
