<?php

namespace Modules\SupportChat\App\Actions;

use App\Models\User;
use Modules\SupportChat\App\Events\MessagesDelivered;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatMessageRepository;

class MarkMessagesAsDeliveredAction
{
    public function __construct(
        private readonly ChatMessageRepository $messages,
    ) {}

    public function execute(ChatRoom $room, User $reader): int
    {
        $updated = $this->messages->markIncomingAsDelivered($room, $reader);

        if ($updated > 0) {
            MessagesDelivered::dispatch($room, $reader, $updated);
        }

        return $updated;
    }
}
