<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\SupportChat\App\Actions\Admin\ListRoomMessagesAction;
use Modules\SupportChat\App\Actions\Admin\SendMessageAction;
use Modules\SupportChat\App\Actions\MarkMessagesAsDeliveredAction;
use Modules\SupportChat\App\Actions\MarkMessagesAsReadAction;
use Modules\SupportChat\App\Http\Requests\MessageIndexRequest;
use Modules\SupportChat\App\Http\Requests\StoreMessageRequest;
use Modules\SupportChat\App\Http\Resources\MessageResource;
use Modules\SupportChat\App\Models\ChatRoom;

class MessageController extends Controller
{
    public function index(
        MessageIndexRequest $request,
        ChatRoom $room,
        ListRoomMessagesAction $action
    ): AnonymousResourceCollection {
        $this->authorize('view', $room);

        return MessageResource::collection(
            $action->execute($room, $request->toData())
        );
    }

    public function store(
        StoreMessageRequest $request,
        ChatRoom $room,
        SendMessageAction $action
    ): MessageResource {
        $this->authorize('sendMessage', $room);

        return new MessageResource(
            $action->execute($room, auth()->user(), $request->toData())
        );
    }

    /**
     * @param ChatRoom $room
     * @param MarkMessagesAsDeliveredAction $action
     * @return JsonResponse
     */
    public function markDelivered(
        ChatRoom $room,
        MarkMessagesAsDeliveredAction $action,
    ): JsonResponse {
        $this->authorize('view', $room);

        $updated = $action->execute($room, auth()->user());

        return response()->json([
            'updated' => $updated,
        ]);
    }

    /**
     * @param ChatRoom $room
     * @param MarkMessagesAsReadAction $action
     * @return JsonResponse
     */
    public function markRead(
        ChatRoom $room,
        MarkMessagesAsReadAction $action
    ): JsonResponse {
        $this->authorize('view', $room);

        $updated = $action->execute($room, auth()->user());

        return response()->json([
            'updated' => $updated,
        ]);
    }
}
