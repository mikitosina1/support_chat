<?php

namespace Modules\SupportChat\App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use Modules\SupportChat\App\Data\StoreMessageData;
use Modules\SupportChat\App\Enums\ChatMessageStatus;
use Modules\SupportChat\App\Models\ChatMessage;
use Modules\SupportChat\App\Models\ChatRoom;

class ChatMessageRepository
{
    public function latestForRoom(
        ChatRoom $room,
        int $limit = 25
    ): Collection {
        return $room->messages()
            ->with(['user.role'])
            ->latest()
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function newAfterId(
        ChatRoom $room,
        int $afterId
    ): Collection {
        return $room->messages()
            ->with(['user.role'])
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();
    }

    public function create(
        ChatRoom $room,
        User $user,
        StoreMessageData $data
    ): ChatMessage {
        /** @var ChatMessage $message */
        $message = $room->messages()->create([
            'user_id' => $user->id,
            'message' => $data->message,
            'status' => ChatMessageStatus::Sent,
        ]);

        $room->touch();

        return $message->load(['user.role']);
    }

    public function markIncomingAsRead(ChatRoom $room, User $reader): int
    {
        return $room->messages()
            ->where('user_id', '!=', $reader->id)
            ->whereIn('status', [
                ChatMessageStatus::Sent,
                ChatMessageStatus::Delivered,
            ])
            ->update([
                'status' => ChatMessageStatus::Read,
            ]);
    }
}
