<?php

namespace Modules\SupportChat\App\Repositories;

use Illuminate\Support\Collection;
use Modules\SupportChat\App\Models\ChatRoom;

class ChatMessageRepository
{
    public function latestForRoom(ChatRoom $room, int $limit = 50): Collection
    {
        return $room->messages()
            ->with(['user.role'])
            ->latest()
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function newAfterId(ChatRoom $room, int $afterId): Collection
    {
        return $room->messages()
            ->with(['user.role'])
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();
    }
}
