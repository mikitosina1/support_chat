<?php

namespace Modules\SupportChat\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController_Extension extends AuthenticatedSessionController
{
	public function store(LoginRequest $request): RedirectResponse
	{
		$response = parent::store($request);

		/* @var User $user */
		$user = Auth::user();
		$tokenName = 'chat_token';

		$request->session()->put($tokenName, [
			'user_id' => $user->id,
			'user_name' => $user->name,
			'user_lastname' => $user->lastname,
			'token' => $user->createToken($tokenName)->plainTextToken
		]);


		return $response;
	}
}
