<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1\Admin;

use Modules\SupportChat\Tests\Feature\Api\V1\SupportChatTest;

class MessageControllerTest extends SupportChatTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_admin_can_view_messages(): void
    {
        $user = $this->actingAsUser();
        $room = $this->createChatRoom($user);
        $this->createChatMessage($room->id, $user->id);

        $admin = $this->actingAsAdmin();
        $this->createChatMessage($room->id, $admin->id);

        $response = $this->getJson(self::ADMIN_BASE_URL."/rooms/{$room->id}/messages");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.user.role', 'admin')
            ->assertJsonPath('data.1.user.role', 'user')
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'message',
                        'status',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'name',
                            'lastname',
                            'email',
                            'profile_photo',
                            'role',
                        ],
                    ],
                ],
            ]);
    }

    public function test_admin_can_store_messages(): void
    {
        $user = $this->actingAsUser();
        $room = $this->createChatRoom($user);

        $admin = $this->actingAsAdmin();

        $response = $this->postJson(self::ADMIN_BASE_URL."/rooms/{$room->id}/messages", [
            'message' => 'test Message Oi',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.message', 'test Message Oi')
            ->assertJsonPath('data.status', 'sent');

        $this->assertDatabaseHas('chat_messages', [
            'message' => 'test Message Oi',
            'status' => 'sent',
        ]);
    }

    public function test_admin_can_mark_as_delivered(): void
    {
        $this->actingAsUser();
        $user = auth()->user();

        $room = $this->createChatRoom($user);
        $userMessage = $this->createChatMessage($room->id, $user->id);

        $admin = $this->actingAsAdmin();
        $adminMessage = $this->createChatMessage($room->id, $admin->id);

        $response = $this->patchJson(self::BASE_URL."/rooms/{$room->id}/messages/delivered");

        $response->assertOk();

        $this->assertDatabaseHas('chat_messages', [
            'id' => $userMessage->id,
            'user_id' => $user->id,
            'status' => 'delivered',
        ]);
        $this->assertDatabaseHas('chat_messages', [
            'id' => $adminMessage->id,
            'user_id' => $admin->id,
            'status' => 'sent',
        ]);
    }

    public function test_admin_can_mark_as_read(): void
    {
        $this->actingAsUser();
        $user = auth()->user();

        $room = $this->createChatRoom($user);
        $userMessage = $this->createChatMessage($room->id, $user->id);

        $admin = $this->actingAsAdmin();
        $adminMessage = $this->createChatMessage($room->id, $admin->id);

        $response = $this->patchJson(self::BASE_URL."/rooms/{$room->id}/messages/read");

        $response->assertOk();

        $this->assertDatabaseHas('chat_messages', [
            'id' => $userMessage->id,
            'user_id' => $user->id,
            'status' => 'read',
        ]);
        $this->assertDatabaseHas('chat_messages', [
            'id' => $adminMessage->id,
            'user_id' => $admin->id,
            'status' => 'sent',
        ]);
    }
}
