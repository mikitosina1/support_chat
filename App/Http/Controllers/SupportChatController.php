<?php

namespace Modules\SupportChat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SupportChat\App\Events\NewMessage;
use Modules\SupportChat\App\Models\ChatRoom;
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
	public function index(): Factory|\Illuminate\Foundation\Application|View|Application
	{
		$isActive = $this->supportChatService->isModuleActive();
		return view('supportchat::support_chat', compact('isActive'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View|\Illuminate\Foundation\Application|Factory|Application
	{
		return view('supportchat::create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
//	public function store(Request $request): RedirectResponse
//	{
//		//
//	}

	/**
	 * Show the specified resource.
	 */
	public function show($id): Factory|\Illuminate\Foundation\Application|View|Application
	{
		return view('supportchat::show');
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit($id): View|\Illuminate\Foundation\Application|Factory|Application
	{
		return view('supportchat::edit');
	}

	/**
	 * Update the specified resource in storage.
	 */
//	public function update(Request $request, $id): RedirectResponse
//	{
//		//
//	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		//
	}

	public function send(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'message' => 'required|string|max:1000',
		]);

		$message = $validated['message'];

		// TODO: use websocket later

		return response()->json([
			'status' => 'ok',
			'message' => $message,
		]);
	}

	public function createRoom(Request $request): JsonResponse
	{
		$room = ChatRoom::create([
			'name' => $request->input('name'),
			'status' => 'open'
		]);

		// Add the creator to the room
		$room->users()->attach(Auth::id());

		return response()->json($room);
	}

	public function sendMessage(Request $request, ChatRoom $room): JsonResponse
	{
		$message = $room->messages()->create([
			'user_id' => Auth::id(),
			'message' => $request->input('message'),
			'status' => 'sent'
		]);

		// Broadcast the message
		broadcast(new NewMessage($message))->toOthers();

		return response()->json($message);
	}

	public function getMessages(ChatRoom $room): JsonResponse
	{
		$messages = $room->messages()
			->with('user')
			->orderBy('created_at', 'asc')
			->get();

		return response()->json($messages);
	}

	public function joinRoom(ChatRoom $room): JsonResponse
	{
		$room->users()->attach(Auth::id());
		return response()->json(['status' => 'success']);
	}

	public function leaveRoom(ChatRoom $room): JsonResponse
	{
		$room->users()->detach(Auth::id());
		return response()->json(['status' => 'success']);
	}
}
