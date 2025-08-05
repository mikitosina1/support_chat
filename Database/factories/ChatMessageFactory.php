<?php

namespace Modules\SupportChat\Database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\SupportChat\App\Models\ChatMessage;
use Modules\SupportChat\App\Models\ChatRoom;


class ChatMessageFactory extends Factory
{
	protected $model = ChatMessage::class;

	public function definition(): array
	{
		return [
			'chat_room_id' => ChatRoom::factory(),
			'user_id' => User::factory(),
			'message' => $this->faker->text(),
			'status' => $this->faker->randomElement(['sent', 'delivered', 'read'])
		];
	}
}
