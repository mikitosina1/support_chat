<?php

namespace Modules\SupportChat\App\Actions\Admin;

use App\Models\User;
use Modules\SupportChat\App\Data\StoreMessageData;
use Modules\SupportChat\App\Models\ChatMessage;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Repositories\ChatMessageRepository;

class SendMessageAction
{
    public function __construct(
        private readonly ChatMessageRepository $messages,
    ) {}

    public function execute(ChatRoom $room, User $user, StoreMessageData $data): ChatMessage
    {
        return $this->messages->create($room, $user, $data);
    }
}
