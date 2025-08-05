<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\SupportChat\Database\factories\ChatMessageFactory;

/**
 * Class ChatRoom
 *
 * Main chat room class definition
 *
 * @property int $id connection id
 * @property int $chat_room_id chat room id
 * @property int $user_id user id
 * @property string $message message text
 * @property string $status status of a message
 * @property Carbon $created_at when created
 * @property Carbon $updated_at when created
 */
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

	/**
	 * redefinition for class factory
	 */
	protected static function newFactory(): ChatMessageFactory
	{
		return ChatMessageFactory::new();
	}
}
