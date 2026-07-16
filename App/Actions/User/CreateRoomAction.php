<?php

namespace Modules\SupportChat\App\Actions\User;

use App\Models\User;
use Modules\SupportChat\App\Data\CreateRoomData;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatRoomRepository;

/**
 * Application action that persists a new chat room for the given user.
 */
class CreateRoomAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    /**
     * @param CreateRoomData $data
     * @param  User $user
     * @return ChatRoom
     */
    public function execute(CreateRoomData $data, User $user): ChatRoom
    {
        return $this->rooms->createForUser($user, $data);
    }
}
