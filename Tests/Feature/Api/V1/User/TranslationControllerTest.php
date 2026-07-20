<?php

namespace Modules\SupportChat\Tests\Feature\Api\V1\User;

use Modules\SupportChat\Tests\Feature\Api\V1\SupportChatTest;

class TranslationControllerTest extends SupportChatTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
    }

    public function test_user_can_get_translations(): void
    {
        $request = $this->getJson(self::BASE_URL.'/translations');

        $request->assertOk()
            ->assertJson(fn ($json) => $json
                ->where('success', true)
                ->has('translations')
                ->etc()
            );
    }
}
