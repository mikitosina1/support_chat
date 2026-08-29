<?php

namespace Modules\SupportChat\App\Policies;

use App\Contracts\ModuleAuthorization;
use App\Models\User;
use Modules\SupportChat\App\Models\ChatRoom;

class ChatRoomPolicy
{
    private const string MODULE = 'support-chat';

    public function __construct(
        private readonly ModuleAuthorization $authorization,
    ) {}

    public function before(
        User $user,
        string $ability
    ): ?bool {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(
        User $user
    ): bool {
        return $this->can($user, 'view');
    }

    public function view(
        User $user,
        ChatRoom $room
    ): bool {
        return $this->can($user, 'view')
            && $this->isParticipant($user, $room);
    }

    protected function isParticipant(
        User $user,
        ChatRoom $room
    ): bool {
        return $room->users()
            ->whereKey($user->id)
            ->exists();
    }

    public function create(
        User $user
    ): bool {
        return $this->can($user, 'create');
    }

    public function resolve(
        User $user,
        ChatRoom $room
    ): bool {
        return $this->can($user, 'resolve')
            && $room->isOpen()
            && $this->isParticipant($user, $room);
    }

    public function close(
        User $user,
        ChatRoom $room
    ): bool {
        return $this->can($user, 'close_room')
            && ! $room->isClosed();
    }

    public function sendMessage(
        User $user,
        ChatRoom $room
    ): bool {
        return $this->can($user, 'send_message')
            && $room->isOpen()
            && $this->isParticipant($user, $room);
    }

    private function can(User $user, string $permission): bool
    {
        return $this->authorization->allows($user, self::MODULE, $permission);
    }
}
