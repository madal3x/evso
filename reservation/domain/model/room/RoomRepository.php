<?php

namespace connected\reservation\domain\model\room;

use connected\common\domain\model\UUID;

interface RoomRepository
{
    /**
     * @param UUID $roomId
     * @return Room
     */
    public function getById(UUID $roomId);

    /**
     * @param Room $room
     * @param $originatingVersion
     */
    public function save(Room $room, $originatingVersion);
}