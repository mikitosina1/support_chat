<?php

namespace Modules\SupportChat\App\Enums;

enum ChatMessageStatus: string
{
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Read = 'read';
}
