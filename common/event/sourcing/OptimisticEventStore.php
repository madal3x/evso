<?php

namespace connected\common\event\sourcing;

use connected\common\domain\model\UUID;
use connected\common\domain\model\Event;

class OptimisticEventStore implements EventStore
{
    private $eventStore;
    private $conflictResolver;

    const MAX_RETRIES_CONCURRENCY_EXCEPTION_RESOLUTION = 5;

    public function __construct(EventStore $eventStore, ConcurrencyConflictResolver $conflictResolver) {
        $this->eventStore = $eventStore;
        $this->conflictResolver = $conflictResolver;
    }

    /**
     * @param UUID $aggregateId
     * @return Event[]
     */
    public function getEvents(UUID $aggregateId)
    {
        return $this->eventStore->getEvents($aggregateId);
    }

    /**
     * @param $events Event[]
     * @param $version int
     * @throws ConcurrencyException
     */
    public function saveChanges($events, $originatingVersion)
    {
        $retries = 0;
        $version = $originatingVersion;
        do {
            $hasConcurrencyException = false;
            try {
                $this->eventStore->saveChanges($events, $version);
            } catch (ConcurrencyException $e) {
                // @todo include concurrency event conflicts in the exception
                if ($this->conflictResolver->hasConflict($events, $e->newEventTypes())) {
                    throw($e);
                } else {
                    $retries++;
                    $hasConcurrencyException = true;
                    // @todo may give problems out-of-sync version => symptom many concurrency exceptions
                    $version += count($e->newEventTypes());
                }
            }
        } while ($hasConcurrencyException === true && $retries <= self::MAX_RETRIES_CONCURRENCY_EXCEPTION_RESOLUTION);
    }
}