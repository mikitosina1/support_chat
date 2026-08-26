<?php

namespace Modules\SupportChat\App\Permissions;

use App\Contracts\ModulePermissions;

final class SupportChatPermissions implements ModulePermissions
{
    public static function all(): array
    {
        return [
            'access',
            'view',
            'send_message',
            'close_room',
        ];
    }

    public static function defaults(): array
    {
        return [
            config('roles.admin') => [
                'access' => true,
                'view' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],

            config('roles.user') => [
                'access' => true,
                'view' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
        ];
    }
}
