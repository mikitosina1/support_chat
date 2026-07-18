<?php

namespace Modules\SupportChat\App\Actions\Admin;

use Modules\SupportChat\App\Events\RoomClosed;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatRoomRepository;

class CloseRoomAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    public function execute(ChatRoom $room): ChatRoom
    {
        $room = $this->rooms->close($room);

        RoomClosed::dispatch($room);

        return $room;
    }
}
