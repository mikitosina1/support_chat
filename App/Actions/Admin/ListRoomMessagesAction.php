<?php

namespace Modules\SupportChat\App\Actions\Admin;

use Illuminate\Support\Collection;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatMessageRepository;

class ListRoomMessagesAction
{
    public function __construct(
        private readonly ChatMessageRepository $messages,
    ) {}

    public function execute(ChatRoom $room): Collection
    {
        return $this->messages->latestForRoom($room);
    }
}
