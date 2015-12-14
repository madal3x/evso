<?php

namespace connected\common\event\sourcing;

class ConcurrencyException extends \Exception
{
    private $aggregateId;
    private $newEventTypes;

    /**
     * @param string $aggregateId
     * @param string[] $newEventTypes
     * @param \Exception $previousException
     */
    public function __construct($aggregateId, $newEventTypes, $previousException) {
        parent::__construct('Concurrency exception for aggregateId: '.$aggregateId, 999999, $previousException);
        $this->aggregateId = $aggregateId;
        $this->newEventTypes = $newEventTypes;
    }

    /**
     * @return mixed
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return mixed
     */
    public function newEventTypes()
    {
        return $this->newEventTypes;
    }
}