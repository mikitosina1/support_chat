<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1\Admin;

use Modules\SupportChat\Tests\Feature\Api\V1\SupportChatTest;

class RoomControllerTest extends SupportChatTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_admin_can_get_rooms_list(): void
    {
        $user = $this->actingAsUser();
        $room = $this->createChatRoom($user);
        $this->createChatMessage($room->id, $user->id);

        $user2 = $this->createUser();
        $room2 = $this->createChatRoom($user2);
        $this->createChatMessage($room2->id, $user2->id);

        $this->actingAsAdmin();

        $response = $this->getJson(self::ADMIN_BASE_URL.'/rooms');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_admin_can_get_data_of_any_room(): void
    {
        $user = $this->actingAsUser();
        $room = $this->createChatRoom($user);

        $this->actingAsAdmin();
        $response = $this->getJson(self::ADMIN_BASE_URL."/rooms/{$room->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_admin_can_close_room(): void
    {
        $user = $this->actingAsUser();
        $room = $this->createChatRoom($user);

        $this->actingAsAdmin();
        $response = $this->patchJson(self::ADMIN_BASE_URL."/rooms/{$room->id}/close");

        $response->assertOk()
            ->assertJsonPath('data.status', 'closed')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
}
