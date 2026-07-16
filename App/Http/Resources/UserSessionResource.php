<?php

namespace Modules\SupportChat\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of the chat session payload produced by {@see SessionDataAction}.
 *
 * @property-read array{
 *     id: int,
 *     name: string,
 *     lastname: string,
 *     email: string,
 *     profile_photo: string|null,
 *     role: string,
 * } $resource
 */
class UserSessionResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array{
     *     success: bool,
     *     data: array<string, mixed>
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'data' => $this->resource,
        ];
    }
}
