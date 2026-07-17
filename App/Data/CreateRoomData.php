<?php

namespace Modules\SupportChat\App\Data;

/**
 * DTO for chat room creation payload.
 *
 * Transfers validated data between controller and service layer.
 */
class CreateRoomData
{
    /**
     * @param string $name
     * @param  string $status
     * @return void
     */
    public function __construct(
        public readonly string $name,
        public readonly string $status,
    ) {}

    /**
     * Create DTO from validated request payload.
     *
     * @param array{
     *     'name': string,
     *     'status': string,
     * } $data
     * @return CreateRoomData
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            status: $data['status'],
        );
    }

    /**
     * Convert DTO into service payload.
     *
     * @return array{
     *     name: string,
     *     status: string,
     * }
     */
    public function toServiceArray(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
