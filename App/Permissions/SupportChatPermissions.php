<?php

namespace Modules\SupportChat\App\Permissions;

use App\Contracts\ModulePermissions;
use App\Models\Role;

final class SupportChatPermissions implements ModulePermissions
{
    public static function all(): array
    {
        return [
            'access',
            'view',
            'create',
            'send_message',
            'resolve',
            'close_room',
        ];
    }

    public static function defaults(): array
    {
        return [
            Role::ADMIN => [
                'access' => true,
                'view' => true,
                'create' => true,
                'send_message' => true,
                'resolve' => true,
                'close_room' => true,
            ],

            Role::USER => [
                'access' => true,
                'view' => true,
                'create' => true,
                'send_message' => true,
                'resolve' => true,
                'close_room' => false,
            ],
        ];
    }
}
