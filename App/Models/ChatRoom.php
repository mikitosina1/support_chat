<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class ChatRoom
 *
 * Main chat room class definition
 *
 * @property int $id basic user id
 * @property string $name chat room title
 * @property string $status chat room status
 * @property Carbon $created_at when created
 * @property Carbon $updated_at when created
 *
 * @method static Builder|ChatRoom whereHas($relation, \Closure $callback = null, $operator = '>=', $count = 1)
 * @method static Builder|ChatRoom where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder|ChatRoom create(array $attributes = [])
 */
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
