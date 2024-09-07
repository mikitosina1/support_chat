<?php

namespace Modules\SupportChat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\SupportChat\Services\SupportChatService;

class SupportChatController extends Controller
{
	protected SupportChatService $supportChatService;

	public function __construct(SupportChatService $supportChatService)
	{
		$this->supportChatService = $supportChatService;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
	{
		$isActive = $this->supportChatService->isModuleActive();
		return view('supportchat::support_chat', compact('isActive'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
	{
		return view('supportchat::create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		//
	}

	/**
	 * Show the specified resource.
	 */
	public function show($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
	{
		return view('supportchat::show');
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
	{
		return view('supportchat::edit');
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, $id): RedirectResponse
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		//
	}
}
