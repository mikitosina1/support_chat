<?php

namespace Modules\SupportChat\App\Actions\User;

use App\Models\User;
use Modules\SupportChat\App\Data\StoreMessageData;
use Modules\SupportChat\App\Events\MessageSent;
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
        $message = $this->messages->create($room, $user, $data);

        MessageSent::dispatch($message);

        return $message;
    }
}
