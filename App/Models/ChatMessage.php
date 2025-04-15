<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;

class ChatMessage extends Model
{
	use HasFactory;

	protected $fillable = [
		'chat_room_id',
		'user_id',
		'message',
		'status'
	];

	protected $casts = [
		'status' => 'string'
	];

	public function chatRoom(): BelongsTo
	{
		return $this->belongsTo(ChatRoom::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function broadcastOn()
	{
		return new PrivateChannel('chat.room.' . $this->chat_room_id);
	}
}
