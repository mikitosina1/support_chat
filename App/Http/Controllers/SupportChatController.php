<?php

namespace Modules\SupportChat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupportChatController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
	{
		var_dump(123);
		return view('supportchat::support_chat');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
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
	public function show($id)
	{
		return view('supportchat::show');
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit($id)
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
