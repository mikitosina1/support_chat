<?php

namespace Modules\SupportChat\App\Repositories;

use Modules\SupportChat\App\Models\ChatRoom;

class ChatRoomRepository
{
    public function __construct(
        private readonly ChatRoom $chatRoom
    ) {}

    public function allForAdmin(): array
    {
        return $this->chatRoom->getAllRooms();
    }
}
