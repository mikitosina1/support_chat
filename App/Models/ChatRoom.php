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
	 * @param $data
	 * @return JsonResponse
	 */
	public function create(array $data): JsonResponse
	{
		$response = ['success' => true];

		return response()->json($response);
	}
}
