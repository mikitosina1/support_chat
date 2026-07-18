<?php

namespace Modules\SupportChat\App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\SupportChat\App\Models\ChatRoom;

class MessagesDelivered
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ChatRoom $room,
        public readonly User $reader,
        public readonly int $updated,
    ) {}
}
