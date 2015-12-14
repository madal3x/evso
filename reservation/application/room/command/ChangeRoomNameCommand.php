<?php

namespace connected\reservation\application\room\command;

use connected\common\domain\model\ConcurrencyAwareCommand;

class ChangeRoomNameCommand extends ConcurrencyAwareCommand
{
    private $roomId;
    private $newName;

    public function __construct($aggregateVersion, $roomId, $newName) {
        parent::__construct($aggregateVersion);
        $this->roomId = $roomId;
        $this->newName = $newName;
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
    public function newName()
    {
        return $this->newName;
    }
}