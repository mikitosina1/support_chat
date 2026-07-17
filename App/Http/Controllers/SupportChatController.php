<?php

namespace Modules\SupportChat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\SupportChat\App\Actions\Admin\ListRoomMessagesAction;
use Modules\SupportChat\App\Actions\Admin\ListRoomsAction;
use Modules\SupportChat\App\Data\MessageIndexData;
use Modules\SupportChat\App\Http\Resources\MessageResource;
use Modules\SupportChat\App\Models\ChatRoom;

class SupportChatController extends Controller
{
    /**
     * Display a listing of chat rooms.
     *
     * @param  ListRoomsAction  $action  class with logic
     * @return View supportchat::supportchat_index with rooms and admin Attributes
     */
    public function index(ListRoomsAction $action): View
    {
        return view('supportchat::supportchat_index', [
            'adminAttr' => auth()->user()->only(['id', 'name', 'lastname', 'email', 'profile_photo']),
            'chatRooms' => $action->execute(),
        ]);
    }

    /**
     * Display selected chat room.
     *
     * @param  ChatRoom  $room  selected chat room
     * @param  ListRoomMessagesAction  $action  class with logic
     * @return View supportchat::room_show with messages and admin attributes
     */
    public function show(
        ChatRoom $room,
        ListRoomMessagesAction $action,
        MessageIndexData $data
    ): View {
        return view('supportchat::room_show', [
            'room' => $room,
            'adminAttr' => auth()->user()->only(['id', 'name', 'lastname', 'email', 'profile_photo']),
            'messages' => MessageResource::collection($action->execute($room, $data))->resolve(),
        ]);
    }
}
