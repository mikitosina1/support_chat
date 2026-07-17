<?php

namespace Modules\SupportChat\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\SupportChat\App\Data\MessageIndexData;

class MessageIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'after_id' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function toData(): MessageIndexData
    {
        return MessageIndexData::fromArray($this->validated());
    }
}
