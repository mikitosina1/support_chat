<?php

namespace Modules\SupportChat\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\SupportChat\App\Models\ChatRoom;

/**
 * JSON representation of a migraine attack for API responses.
 *
 * @mixin ChatRoom
 */
class ChatRoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'room' => [
                'id' => $this->id,
                'name' => $this->name,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
