<?php

namespace Modules\SupportChat\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\SupportChat\App\Models\ChatRoom;

class ChatRoomFactory extends Factory
{
	protected $model = ChatRoom::class;

	public function definition(): array
	{
		return [
			'name' => $this->faker->words(3, true),
			'status' => $this->faker->randomElement(['open', 'closed'])
		];
	}
}
