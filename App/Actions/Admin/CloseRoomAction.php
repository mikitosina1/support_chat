<?php

namespace Modules\SupportChat\App\Actions\Admin;

use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatRoomRepository;

class CloseRoomAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    public function execute(ChatRoom $room): ChatRoom
    {
        return $this->rooms->close($room);
    }
}
