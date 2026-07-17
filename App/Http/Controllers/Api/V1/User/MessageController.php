<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\SupportChat\App\Actions\MarkMessagesAsDeliveredAction;
use Modules\SupportChat\App\Actions\MarkMessagesAsReadAction;
use Modules\SupportChat\App\Actions\User\ListMyRoomMessagesAction;
use Modules\SupportChat\App\Actions\User\SendMessageAction;
use Modules\SupportChat\App\Http\Requests\MessageIndexRequest;
use Modules\SupportChat\App\Http\Requests\StoreMessageRequest;
use Modules\SupportChat\App\Http\Resources\MessageResource;
use Modules\SupportChat\App\Models\ChatRoom;

class MessageController extends Controller
{
    /**
     * @param MessageIndexRequest $request
     * @param ChatRoom $room
     * @param ListMyRoomMessagesAction $action
     * @return AnonymousResourceCollection
     */
    public function index(
        MessageIndexRequest $request,
        ChatRoom $room,
        ListMyRoomMessagesAction $action
    ): AnonymousResourceCollection {
        $this->authorize('view', $room);

        return MessageResource::collection(
            $action->execute($room, $request->toData())
        );
    }

    /**
     * @param StoreMessageRequest $request
     * @param ChatRoom $room
     * @param SendMessageAction $action
     * @return MessageResource
     */
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
