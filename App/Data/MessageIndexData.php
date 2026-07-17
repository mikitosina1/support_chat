<?php

namespace Modules\SupportChat\App\Data;

class MessageIndexData
{
    public function __construct(
        public readonly int $afterId = 0,
        public readonly int $limit = 50,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            afterId: (int) ($data['after_id'] ?? 0),
            limit: min((int) ($data['limit'] ?? 50), 100),
        );
    }
}
