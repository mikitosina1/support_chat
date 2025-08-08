<?php

namespace Modules\SupportChat\App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\SupportChat\App\Models\ChatMessage;

class NewMessage implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public ChatMessage $message;

	public function __construct(ChatMessage $message)
	{
		$this->message = $message->load(['user.role']);
	}

	public function broadcastOn(): PrivateChannel
	{
		return new PrivateChannel('chat.room.' . $this->message->chat_room_id);
	}

	public function broadcastAs(): string
	{
		return 'new.message';
	}

	public function broadcastWith(): array
	{
		$user = $this->message->user;
		return [
			'id' => $this->message->id,
			'message' => $this->message->message,
			'status' => $this->message->status,
			'created_at' => $this->message->created_at?->format('H:i'),
			'updated_at' => $this->message->updated_at?->format('H:i'),
			'user' => [
				'name' => $user?->name,
				'lastname' => $user?->lastname,
				'email' => $user?->email,
				'profile_photo' => $user?->profile_photo,
				'role' => optional($user?->role)->title ?? 'user',
			],
		];
	}
}
