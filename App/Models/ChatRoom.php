<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class ChatRoom extends Model
{
	protected $fillable = [
		'name',
		'status'
	];

	protected $casts = [
		'status' => 'string'
	];

	public function messages(): HasMany
	{
		return $this->hasMany(ChatMessage::class);
	}

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'chat_room_users')
			->withTimestamps();
	}

	/**
	 * creates a new chat room
	 * @param array $data
	 * @return ChatRoom
	 */
	public function create(array $data): ChatRoom
	{
		// Create the chat room
		$room = new self();
		$room->name = $data['name'];
		$room->status = $data['status'] ?? 'open';
		$room->save();

		// Associate the current user with the room
		if (auth()->check()) {
			$room->users()->attach(auth()->id());
		}

		return $room;
	}
}
