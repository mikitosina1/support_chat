<?php

namespace Modules\SupportChat\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\SupportChat\App\Data\StoreMessageData;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|min:1|max:2000',
        ];
    }

    public function toData(): StoreMessageData
    {
        return StoreMessageData::fromArray($this->validated());
    }
}
