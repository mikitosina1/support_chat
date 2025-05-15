<?php

namespace Modules\SupportChat\App\Http\Resources;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * MessageResource
 *
 * Class created to make the correct answer for frontend
 *
 * @property int $id
 * @property string $message text of a message
 * @property string $status message status
 * @property Carbon $created_at when created
 * @property Carbon $updated_at when created
 * @property User $user creator
 * @property-read ?Role $role->title users role
 */
class MessageResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'message' => $this->message,
			'status' => $this->status,
			'created_at' => $this->created_at->format('H:i'),
			'updated_at' => $this->updated_at->format('H:i'),
			'user' => [
				'name' => $this->user->name,
				'lastname' => $this->user->lastname,
				'email' => $this->user->email,
				'profile_photo' => $this->user->profile_photo,
				'role' => optional($this->user->role)->title ?? 'user'
			]
		];
	}
}
