<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\SupportChat\Database\factories\ChatRoomFactory;

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
 * @method static Builder|ChatRoom whereHas($relation, Closure $callback = null, $operator = '>=', $count = 1)
 * @method static Builder|ChatRoom where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder|ChatRoom create(array $attributes = [])
 */
class ChatRoom extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'status'
	];

	protected $casts = [
		'status' => 'string'
	];

	/**
	 * redefinition for class factory
	 */
	protected static function newFactory(): ChatRoomFactory
	{
		return ChatRoomFactory::new();
	}

	public function messages(): HasMany
	{
		return $this->hasMany(ChatMessage::class);
	}

	/**
	 * creates a new chat room
	 * @param array $data
	 * @return ChatRoom
	 */
	public function create(array $data): ChatRoom
	{
		// Create a chat room
		$room = new self();
		$room->name = $data['name'];
		$room->status = $data['status'] ?? 'open';
		$room->save();

		// Associate a current user with the room
		if (auth()->check())
			$room->users()->attach(auth()->id());

		return $room;
	}

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'chat_room_users')
			->withTimestamps();
	}

	/**
	 * gets a room list
	 * @return array|
	 */
	public function getAllRooms(): array
	{
		return self::select([
			'id',
			'name',
			'status',
			'created_at',
			'updated_at'
		])
			->with([
				'users:id,name,lastname,email',
				'messages:id,chat_room_id,user_id,message,status,created_at'
			])
			->withCount('messages')
			->orderBy('updated_at', 'desc')
			->get()
			->map(function ($room) {
				return [
					'id' => $room->id,
					'name' => $room->name,
					'status' => $room->status,
					'created_at' => $room->created_at->format('d.m.Y H:i'),
					'updated_at' => $room->updated_at->format('d.m.Y H:i'),
					'created_human' => $room->created_at->diffForHumans(),
					'updated_human' => $room->updated_at->diffForHumans(),
					'messages_count' => $room->messages_count,
					'users' => $room->users,
					'messages' => $room->messages
				];
			})
			->toArray();
	}
}
