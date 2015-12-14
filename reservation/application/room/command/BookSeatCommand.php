<?php

namespace connected\reservation\application\room\command;

use connected\common\domain\model\ConcurrencyAwareCommand;

class BookSeatCommand extends ConcurrencyAwareCommand
{
    private $roomId;

    public function __construct($aggregateVersion, $roomId) {
        parent::__construct($aggregateVersion);
        $this->roomId = $roomId;
    }

    /**
     * @return mixed
     */
    public function roomId()
    {
        return $this->roomId;
    }
}