<?php

namespace Modules\SupportChat\App\Actions\User;

use App\Models\User;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatRoomRepository;

class GetCurrentRoomAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    public function execute(User $user): ChatRoom
    {
        return $this->rooms->getOrCreateOpenForUser($user);
    }
}
