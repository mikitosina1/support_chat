<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1\User;

use Modules\SupportChat\Tests\Feature\Api\V1\SupportChatTest;

class MessageControllerTest extends SupportChatTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
    }

    public function test_user_get_current_room_message(): void
    {
        $user = auth()->user();
        $room = $this->createChatRoom($user);
        $message = $this->createChatMessage($room->id, $user->id);

        $response = $this->getJson(self::BASE_URL."/rooms/{$room->id}/messages");

        $response->assertOk()
            ->assertJsonPath('data.0.id', $message->id)
            ->assertJsonPath('data.0.message', $message->message)
            ->assertJsonPath('data.0.status', $message->status->value);
    }

    public function test_user_can_create_message_in_current_room(): void
    {
        $user = auth()->user();
        $room = $this->createChatRoom($user);

        $response = $this->postJson(self::BASE_URL."/rooms/{$room->id}/messages", [
            'message' => 'message for Feature Test',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.message', 'message for Feature Test')
            ->assertJsonPath('data.status', 'sent');

        $this->assertDatabaseHas('chat_messages', [
            'message' => 'message for Feature Test',
            'status' => 'sent',
        ]);
    }

    public function test_user_can_mark_as_delivered(): void
    {
        $user = auth()->user();
        $room = $this->createChatRoom($user);
        $userMessage = $this->createChatMessage($room->id, $user->id);

        $admin = $this->createAdmin();
        $adminMessage = $this->createChatMessage($room->id, $admin->id);

        $response = $this->patchJson(self::BASE_URL."/rooms/{$room->id}/messages/delivered");

        $response->assertOk();

        $this->assertDatabaseHas('chat_messages', [
            'id' => $userMessage->id,
            'user_id' => $user->id,
            'status' => 'sent',
        ]);
        $this->assertDatabaseHas('chat_messages', [
            'id' => $adminMessage->id,
            'user_id' => $admin->id,
            'status' => 'delivered',
        ]);
    }

    public function test_user_can_mark_as_read(): void
    {
        $user = auth()->user();
        $room = $this->createChatRoom($user);
        $userMessage = $this->createChatMessage($room->id, $user->id);

        $admin = $this->createAdmin();
        $adminMessage = $this->createChatMessage($room->id, $admin->id);

        $response = $this->patchJson(self::BASE_URL."/rooms/{$room->id}/messages/read");

        $response->assertOk();

        $this->assertDatabaseHas('chat_messages', [
            'id' => $userMessage->id,
            'user_id' => $user->id,
            'status' => 'sent',
        ]);
        $this->assertDatabaseHas('chat_messages', [
            'id' => $adminMessage->id,
            'user_id' => $admin->id,
            'status' => 'read',
        ]);
    }
}
