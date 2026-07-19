<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1\User;

use Modules\SupportChat\Tests\Feature\Api\V1\SupportChatTest;

class RoomControllerTest extends SupportChatTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
    }

    public function test_user_get_current_room(): void
    {
        $room = $this->createChatRoom(auth()->user());
        $response = $this->getJson(self::BASE_URL.'/rooms/current');

        $response->assertOk()
            ->assertJsonPath('data.id', $room->id)
            ->assertJsonPath('data.name', $room->name)
            ->assertJsonPath('data.status', $room->status->value);
    }

    public function test_user_store_room(): void
    {
        $data = [
            'name' => 'Test Room',
        ];

        $response = $this->postJson(self::BASE_URL.'/rooms', $data);

        $response->assertCreated()
            ->assertJsonPath('data.name', $data['name']);

        $this->assertDatabaseHas('chat_rooms', $data);
    }

    public function test_user_can_resolve_room(): void
    {
        $room = $this->createChatRoom(auth()->user());

        $response = $this->patchJson(self::BASE_URL."/rooms/{$room->id}/resolve");

        $response->assertOk()
            ->assertJsonPath('data.name', $room->name)
            ->assertJsonPath('data.status', 'resolved');
    }
}
