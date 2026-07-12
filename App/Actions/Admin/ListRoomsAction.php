<?php

namespace Modules\SupportChat\App\Actions\Admin;

use Modules\SupportChat\App\Repositories\ChatRoomRepository;

class ListRoomsAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    public function execute(): array
    {
        return $this->rooms->allForAdmin();
    }
}
