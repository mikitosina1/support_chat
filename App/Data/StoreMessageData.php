<?php

namespace Modules\SupportChat\App\Data;

class StoreMessageData
{
    public function __construct(
        public readonly string $message,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            message: $data['message'],
        );
    }
}
