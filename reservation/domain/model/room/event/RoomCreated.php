<?php

namespace connected\reservation\domain\model\room\event;

use connected\common\domain\model\Event;
use connected\common\domain\model\UUID;

class RoomCreated extends Event
{
    private $roomName;
    private $seatCapacity;

    public function __construct(UUID $roomId, $name, $seatCapacity) {
        parent::__construct($roomId);
        $this->roomName = $name;
        $this->seatCapacity = $seatCapacity;
    }

    /**
     * @return mixed
     */
    public function roomName()
    {
        return $this->roomName;
    }

    /**
     * @return mixed
     */
    public function seatCapacity()
    {
        return $this->seatCapacity;
    }
}