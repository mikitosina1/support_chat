<?php

namespace Modules\SupportChat\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\SupportChat\App\Data\CreateRoomData;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function toData(): createRoomData
    {
        return CreateRoomData::fromArray($this->validated());
    }
}
