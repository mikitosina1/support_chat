<?php

namespace Modules\SupportChat\App\Actions\User;

use App\Models\User;
use Modules\SupportChat\App\Events\RoomResolved;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatRoomRepository;

class ResolveRoomAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    public function execute(ChatRoom $room, User $user): ChatRoom
    {
        $room = $this->rooms->resolve($room, $user);

        RoomResolved::dispatch($room);

        return $room;
    }
}
