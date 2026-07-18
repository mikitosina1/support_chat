<?php

namespace Modules\SupportChat\App\Actions;

use App\Models\User;
use Modules\SupportChat\App\Events\MessagesRead;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatMessageRepository;

class MarkMessagesAsReadAction
{
    public function __construct(
        private readonly ChatMessageRepository $messages,
    ) {}

    public function execute(ChatRoom $room, User $reader): int
    {
        $updated = $this->messages->markIncomingAsRead($room, $reader);

        if ($updated > 0) {
            MessagesRead::dispatch($room, $reader, $updated);
        }

        return $updated;
    }
}
