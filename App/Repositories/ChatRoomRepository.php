<?php

namespace Modules\SupportChat\App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\SupportChat\App\Data\CreateRoomData;
use Modules\SupportChat\App\Enums\ChatRoomStatus;
use Modules\SupportChat\App\Models\ChatRoom;

class ChatRoomRepository
{
    public function allForAdmin(): Collection
    {
        return ChatRoom::query()
            ->select(['id', 'name', 'status', 'created_at', 'updated_at'])
            ->with([
                'users:id,name,lastname,email,profile_photo',
                'messages:id,chat_room_id,user_id,message,status,created_at',
            ])
            ->withCount('messages')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function findForAdminOrFail(int $roomId): ChatRoom
    {
        return ChatRoom::query()
            ->with(['users', 'messages.user.role'])
            ->findOrFail($roomId);
    }

    public function getOrCreateOpenForUser(User $user): ChatRoom
    {
        return $this->findOpenForUser($user)
            ?? $this->createForUser($user);
    }

    public function findOpenForUser(User $user): ?ChatRoom
    {
        return ChatRoom::query()
            ->where('status', ChatRoomStatus::Open)
            ->whereHas('users', fn ($query) => $query->whereKey($user->id))
            ->latest('updated_at')
            ->first();
    }

    public function createForUser(User $user, ?CreateRoomData $data = null): ChatRoom
    {
        $room = ChatRoom::query()->create([
            'name' => $data?->name ?? 'Support chat #'.$user->id,
            'status' => ChatRoomStatus::Open,
        ]);

        $room->users()->syncWithoutDetaching([$user->id]);

        return $room->load('users');
    }

    public function resolve(ChatRoom $room, User $user): ChatRoom
    {
        $room = $this->findForUserOrFail($room->id, $user);

        $room->update([
            'status' => ChatRoomStatus::Resolved,
        ]);

        return $room->refresh();
    }

    public function findForUserOrFail(int $roomId, User $user): ChatRoom
    {
        /** @var ChatRoom|null $room */
        $room = ChatRoom::query()
            ->whereKey($roomId)
            ->whereHas('users', fn ($query) => $query->whereKey($user->id))
            ->first();

        if (! $room) {
            throw (new ModelNotFoundException)->setModel(ChatRoom::class, [$roomId]);
        }

        return $room;
    }

    public function close(ChatRoom $room): ChatRoom
    {
        $room->fill([
            'status' => ChatRoomStatus::Closed,
        ])->save();

        return $room->refresh();
    }

    public function reopen(ChatRoom $room): ChatRoom
    {
        $room->fill([
            'status' => ChatRoomStatus::Open,
        ])->save();

        return $room->refresh();
    }

    public function touchActivity(ChatRoom $room): void
    {
        $room->touch();
    }
}
