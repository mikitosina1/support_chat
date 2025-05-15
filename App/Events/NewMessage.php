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
		$this->message = $message->load('user');
	}

	public function broadcastOn(): PrivateChannel
	{
		return new PrivateChannel('chat.room.' . $this->message->__get("chat_room_id"));
	}

	public function broadcastAs(): string
	{
		return 'new.message';
	}
}
