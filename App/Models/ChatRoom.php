<?php

namespace Modules\SupportChat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\SupportChat\Database\factories\ChatRoomFactory;

/**
 * Class ChatRoom
 *
 * Main chat room class definition
 *
 * @property int $id basic user id
 * @property string $name chat room title
 * @property string $status chat room status
 * @property Carbon $created_at when created
 * @property Carbon $updated_at when created
 */
class ChatRoom extends Model
{
    use HasFactory;

    const STATUS_OPEN = 'open';

    const STATUS_CLOSED = 'closed';

    const STATUS_RESOLVED = 'resolved';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Redefinition for class factory
     */
    protected static function newFactory(): ChatRoomFactory
    {
        return ChatRoomFactory::new();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_room_users')
            ->withTimestamps();
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }
}
