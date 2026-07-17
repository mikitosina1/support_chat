<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\SupportChat\App\Enums\ChatMessageStatus;
use Modules\SupportChat\Database\factories\ChatMessageFactory;

/**
 * Class ChatRoom
 *
 * Main chat room class definition
 *
 * @property int $id connection id
 * @property int $chat_room_id chat room id
 * @property int $user_id user id
 * @property string $message message text
 * @property ChatMessageStatus $status status of a message
 * @property Carbon $created_at when created
 * @property Carbon $updated_at when created
 */
class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => ChatMessageStatus::class,
    ];

    /**
     * redefinition for class factory
     */
    protected static function newFactory(): ChatMessageFactory
    {
        return ChatMessageFactory::new();
    }

    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
