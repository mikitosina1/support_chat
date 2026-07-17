<?php

namespace Modules\SupportChat\App\Enums;

enum ChatRoomStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
    case Resolved = 'resolved';
}
