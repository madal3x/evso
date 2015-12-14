<?php

namespace connected\common\event\sourcing;

use connected\common\domain\model\EventSourcedAggregateRoot;
use connected\common\domain\model\UUID;

class EventSourcedRepository
{
    /** @var EventStore */
    protected $eventStore;

    public function __construct(EventStore $eventStore) {
        $this->eventStore = $eventStore;
    }

    public function getById(UUID $aggregateId, $aggregateFullyQualifiedClassName) {
        $events = $this->eventStore->getEvents($aggregateId);

        if ($events) {
            $aggregate = new $aggregateFullyQualifiedClassName($events);

            return $aggregate;
        }

        return null;
    }

    public function save(EventSourcedAggregateRoot $aggregateRoot, $originatingVersion) {
        $this->eventStore->saveChanges($aggregateRoot->uncommittedEvents(), $originatingVersion);
    }
}