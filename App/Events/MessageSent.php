<?php

namespace Modules\SupportChat\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\SupportChat\App\Models\ChatMessage;

class MessageSent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ChatMessage $message,
    ) {}
}
