<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\SupportChat\App\Actions\Admin\ListRoomMessagesAction;
use Modules\SupportChat\App\Actions\Admin\SendMessageAction;
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
}
