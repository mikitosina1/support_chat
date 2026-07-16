<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Modules\SupportChat\App\Actions\User\CreateRoomAction;
use Modules\SupportChat\App\Actions\User\GetCurrentRoomAction;
use Modules\SupportChat\App\Actions\User\ResolveRoomAction;
use Modules\SupportChat\App\Http\Requests\StoreRoomRequest;
use Modules\SupportChat\App\Http\Resources\ChatRoomResource;
use Modules\SupportChat\App\Models\ChatRoom;

class RoomController extends Controller
{
    /**
     * @param GetCurrentRoomAction $action
     * @return ChatRoomResource
     */
    public function current(GetCurrentRoomAction $action): ChatRoomResource
    {
        return new ChatRoomResource(
            $action->execute(auth()->user())
        );
    }

    /**
     * @param StoreRoomRequest $request
     * @param CreateRoomAction $action
     * @return ChatRoomResource
     */
    public function store(
        StoreRoomRequest $request,
        CreateRoomAction $action,
    ): ChatRoomResource {

        return new ChatRoomResource(
            $action->execute($request->toData(), auth()->user())
        );
    }

    /**
     * @param ChatRoom $room
     * @param ResolveRoomAction $action
     * @return ChatRoomResource
     */
    public function resolve(
        ChatRoom $room,
        ResolveRoomAction $action
    ): ChatRoomResource {

        return new ChatRoomResource(
            $action->execute($room, auth()->user())
        );
    }
}
