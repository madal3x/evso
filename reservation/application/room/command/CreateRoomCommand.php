<?php

namespace connected\reservation\application\room\command;

use connected\common\domain\model\ConcurrencyAwareCommand;
use connected\common\domain\model\UUID;

class CreateRoomCommand extends ConcurrencyAwareCommand
{
    private $roomId;
    private $name;
    private $seatCapacity;

    /**
     * @param $roomId string
     * @param $roomVersion int
     * @param $name string
     * @param $seatCapacity int
     */
    public function __construct($roomId, $roomVersion, $name, $seatCapacity) {
        parent::__construct($roomVersion);
        $this->roomId = $roomId;
        $this->name = $name;
        $this->seatCapacity = $seatCapacity;
    }

    /**
     * @return mixed
     */
    public function roomId()
    {
        return $this->roomId;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function seatCapacity()
    {
        return $this->seatCapacity;
    }
}