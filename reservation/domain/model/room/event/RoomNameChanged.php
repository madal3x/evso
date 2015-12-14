<?php

namespace connected\reservation\domain\model\room\event;

use connected\common\domain\model\Event;
use connected\common\domain\model\UUID;

class RoomNameChanged extends Event
{
    private $newName;

    public function __construct(UUID $roomId, $newName) {
        parent::__construct($roomId);
        $this->newName = $newName;
    }

    /**
     * @return mixed
     */
    public function newName()
    {
        return $this->newName;
    }
}