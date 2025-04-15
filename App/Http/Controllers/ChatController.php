<?php

namespace Modules\SupportChat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SupportChat\App\Models\ChatRoom;
use Modules\SupportChat\App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function createRoom(Request $request)
    {
        $room = ChatRoom::create([
            'name' => $request->input('name'),
            'status' => 'open'
        ]);

        // Add the creator to the room
        $room->users()->attach(Auth::id());

        return response()->json($room);
    }

    public function sendMessage(Request $request, ChatRoom $room)
    {
        $message = $room->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->input('message'),
            'status' => 'sent'
        ]);

        // Broadcast the message
        broadcast(new \Modules\SupportChat\App\Events\NewMessage($message))->toOthers();

        return response()->json($message);
    }

    public function getMessages(ChatRoom $room)
    {
        $messages = $room->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function joinRoom(ChatRoom $room)
    {
        $room->users()->attach(Auth::id());
        return response()->json(['status' => 'success']);
    }

    public function leaveRoom(ChatRoom $room)
    {
        $room->users()->detach(Auth::id());
        return response()->json(['status' => 'success']);
    }
} 