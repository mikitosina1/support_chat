<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\SupportChat\App\Actions\Admin\CloseRoomAction;
use Modules\SupportChat\App\Actions\Admin\ListRoomsAction;
use Modules\SupportChat\App\Actions\Admin\ShowRoomAction;
use Modules\SupportChat\App\Http\Resources\ChatRoomResource;
use Modules\SupportChat\App\Models\ChatRoom;

class AdminRoomController extends Controller
{
    public function index(ListRoomsAction $action): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ChatRoom::class);

        return ChatRoomResource::collection(
            $action->execute()
        );
    }

    public function show(ChatRoom $room, ShowRoomAction $action): ChatRoomResource
    {
        $this->authorize('view', $room);

        return new ChatRoomResource(
            $action->execute($room)
        );
    }

    public function close(ChatRoom $room, CloseRoomAction $action): ChatRoomResource
    {
        $this->authorize('close', $room);

        return new ChatRoomResource(
            $action->execute($room)
        );
    }
}
