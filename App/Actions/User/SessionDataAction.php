<?php

namespace Modules\SupportChat\App\Actions\User;

use App\Models\User;

/**
 * Application action that assembles all data required for the user session for chat.
 */
class SessionDataAction
{
    /**
     * @param int $userId
     * @return array{
     *     id: int,
     *     name: string,
     *     lastname: string,
     *     email: string,
     *     profile_photo: string|null,
     *     role: string,
     * }
     */
    public function execute(int $userId): array
    {
        $currentUser = User::query()->findOrFail($userId);

        return [
            'user' => [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'lastname' => $currentUser->lastname,
                'email' => $currentUser->email,
                'profile_photo' => $currentUser->profile_photo,
                'role' => optional($currentUser->role)->title ?? 'user',
            ],
        ];
    }
}
