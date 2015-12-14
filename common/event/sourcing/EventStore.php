<?php

namespace connected\common\event\sourcing;

use connected\common\domain\model\UUID;
use connected\common\domain\model\Event;

interface EventStore {
    /**
     * @param UUID $aggregateId
     * @return Event[]
     */
    public function getEvents(UUID $aggregateId);

    /**
     * @param $events Event[]
     * @param $originatingVersion int
     * @throws ConcurrencyException
     */
    public function saveChanges($events, $originatingVersion);
}