<?php

namespace Modules\SupportChat\App\Actions\Admin;

use Illuminate\Support\Collection;
use Modules\SupportChat\App\Repositories\ChatRoomRepository;

class ListRoomsAction
{
    public function __construct(
        private readonly ChatRoomRepository $rooms,
    ) {}

    public function execute(): Collection
    {
        return $this->rooms->allForAdmin();
    }
}
