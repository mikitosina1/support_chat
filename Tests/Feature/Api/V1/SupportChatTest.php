<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\SupportChat\App\Enums\ChatMessageStatus;
use Modules\SupportChat\App\Enums\ChatRoomStatus;
use Modules\SupportChat\App\Models\ChatMessage;
use Modules\SupportChat\App\Models\ChatRoom;
use Tests\TestCase;

class SupportChatTest extends TestCase
{
    use RefreshDatabase;

    protected const BASE_URL = '/api/v1/support-chat';

    protected const ADMIN_BASE_URL = '/api/v1/admin/support-chat';

    /**
     * acting test user as simple
     */
    protected function actingAsUser(): User
    {
        $user = $this->createUser();

        Sanctum::actingAs($user);

        return $user;
    }

    /**
     * creates simple user
     *
     * @return User
     */
    protected function createUser(): User
    {
        $role = Role::firstOrCreate(['title' => Role::USER]);

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    /**
     * acting test user as admin
     *
     * @return User
     */
    protected function actingAsAdmin(): User
    {
        $admin = $this->createAdmin();

        Sanctum::actingAs($admin);

        return $admin;
    }

    /**
     * creates admin user
     *
     * @return User
     */
    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['title' => Role::ADMIN]);

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    /**
     * creates chat room
     *
     * @param User $user
     * @return ChatRoom
     */
    protected function createChatRoom(User $user): ChatRoom
    {
        $room = ChatRoom::factory()->create([
            'status' => ChatRoomStatus::Open,
        ]);

        $room->users()->attach($user);

        return $room;
    }

    protected function createChatMessage(int $chatRoomId, int $uid): ChatMessage
    {
        return ChatMessage::factory()->create(['chat_room_id' => $chatRoomId, 'user_id' => $uid, 'status' => ChatMessageStatus::Sent]);
    }
}
