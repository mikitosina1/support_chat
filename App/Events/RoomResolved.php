<?php

namespace Modules\SupportChat\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\SupportChat\App\Models\ChatRoom;

class RoomResolved
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ChatRoom $room,
    ) {}
}
