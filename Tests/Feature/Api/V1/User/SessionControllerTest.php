<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1\User;

use Modules\SupportChat\Tests\Feature\Api\V1\SupportChatTest;

class SessionControllerTest extends SupportChatTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
    }

    public function test_user_can_get_session(): void
    {
        $user = auth()->user();
        $response = $this->getJson(self::BASE_URL.'/session');

        $response->assertOk()
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.user.name', $user->name)
            ->assertJsonPath('data.user.email', $user->email)
            ->assertJsonPath('data.user.role', $user->role->title);
    }
}
