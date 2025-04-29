<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
	private mixed $chat_room_id;

	public function chatRoom(): BelongsTo
	{
		return $this->belongsTo(ChatRoom::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
