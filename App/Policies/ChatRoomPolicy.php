<?php

namespace Modules\SupportChat\App\Policies;

use App\Models\User;
use Modules\SupportChat\App\Models\ChatRoom;

class ChatRoomPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ChatRoom $room): bool
    {
        return $this->isParticipant($user, $room);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function join(User $user, ChatRoom $room): bool
    {
        return false;
    }

    public function leave(User $user, ChatRoom $room): bool
    {
        return $this->isParticipant($user, $room);
    }

    public function sendMessage(User $user, ChatRoom $room): bool
    {
        return $room->status === 'open'
            && $this->isParticipant($user, $room);
    }

    protected function isParticipant(User $user, ChatRoom $room): bool
    {
        return $room->users()
            ->whereKey($user->id)
            ->exists();
    }
}
