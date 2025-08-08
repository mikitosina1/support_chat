<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\SupportChat\App\Models\ChatRoom;

Broadcast::channel('chat.room.{roomId}', function ($user, $roomId) {
	if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
		return true;
	}
	return ChatRoom::whereKey($roomId)
		->whereHas('users', fn($q) => $q->where('users.id', $user->id))
		->exists();
});
