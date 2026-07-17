<?php

namespace Modules\SupportChat\App\Actions\Admin;

use Illuminate\Support\Collection;
use Modules\SupportChat\App\Data\MessageIndexData;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatMessageRepository;

class ListRoomMessagesAction
{
    public function __construct(
        private readonly ChatMessageRepository $messages,
    ) {}

    public function execute(ChatRoom $room, MessageIndexData $data): Collection
    {
        if ($data->afterId > 0) {
            return $this->messages->newAfterId($room, $data->afterId);
        }

        return $this->messages->latestForRoom($room, $data->limit);
    }
}
