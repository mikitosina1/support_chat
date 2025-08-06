<?php

namespace Modules\SupportChat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\SupportChat\App\Http\Resources\MessageResource;
use Modules\SupportChat\App\Models\ChatMessage;
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
	public function index(): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
	{
		$isActive = $this->supportChatService->isModuleActive();
		$user = auth()->user();
		if (!$isActive && !$user->isAdmin())
			return response()->redirectTo('/');
		$userAttr = $user->only([
			'id',
			'name',
			'lastname',
			'email',
			'profile_photo'
		]);
		return view('supportchat::supportchat_index', compact('isActive', 'userAttr'));
	}

	public function createRoom(Request $request): JsonResponse
	{
		$request->validate([
			'name' => 'required|string|max:255',
		]);

		$room = new ChatRoom();
		$room = $room->create([
			'name' => $request->name,
			'status' => 'open'
		]);

		return response()->json([
			'success' => true,
			'room' => $room
		]);
	}

	public function joinRoom(ChatRoom $room): JsonResponse
	{
		if (auth()->check()) {
			$room->users()->attach(auth()->id());
		}

		return response()->json([
			'success' => true
		]);
	}

	public function leaveRoom(ChatRoom $room): JsonResponse
	{
		if (auth()->check()) {
			$room->users()->detach(auth()->id());
		}

		return response()->json([
			'success' => true
		]);
	}

	public function sendMessage(Request $request, ChatRoom $room): JsonResponse
	{
		$request->validate([
			'message' => 'required|string'
		]);

		$message = new ChatMessage();
		$message->chat_room_id = $room->id;
		$message->user_id = auth()->id();
		$message->message = $request->message;
		$message->status = 'sent';
		$message->save();

		return response()->json([
			'success' => true,
			'message' => $message,
			'room_id' => $room->id
		]);
	}

	public function getMessages(ChatRoom $room): JsonResponse
	{
		$messages = $room->messages()
			->with(['user.role'])
			->latest()
			->take(50)
			->get()
			->reverse()
			->values();

		return response()->json([
			'success' => true,
			'messages' => MessageResource::collection($messages)
		]);
	}

	public function getOrCreateRoom(Request $request): JsonResponse
	{
		// for authorized
		if (auth()->check()) {
			$room = ChatRoom::whereHas('users', function ($query) {
				$query->where('user_id', auth()->id());
			})->first();

			if (!$room) {
				$room = new ChatRoom();
				$room = $room->create([
					'name' => 'Support chat #' . auth()->id(),
					'status' => 'open'
				]);
			}

			return response()->json([
				'success' => true,
				'room' => $room
			]);
		}

		// for unauthorized users
		$sessionId = $request->session()->getId();
		$room = ChatRoom::where('name', 'Guest Chat ' . $sessionId)->first();

		if (!$room) {
			$room = new ChatRoom();
			$room = $room->create([
				'name' => 'Guest Chat ' . $sessionId,
				'status' => 'open'
			]);
		}

		return response()->json([
			'success' => true,
			'room' => $room
		]);
	}

	public function getTranslations(Request $request): JsonResponse
	{
		$locale = $request->header('Accept-Language') ?? app()->getLocale();
		$locale = substr($locale, 0, 2);

		$availableLocales = ['en', 'de', 'ru'];
		if (!in_array($locale, $availableLocales)) {
			$locale = 'en'; // basic translation
		}

		$translations = trans('chat_lang', [], $locale);

		return response()->json([
			'success' => true,
			'translations' => $translations
		]);
	}

}
