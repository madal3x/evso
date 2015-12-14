<?php

namespace connected\common\event\sourcing;

use connected\common\domain\model\Event;

class ConcurrencyConflictResolver
{
    private $registry;

    public function __construct() {
        $this->registry = array();
    }

    /** @todo add interface */
    /**
     * if not registered, it conflicts by default
     *
     * @param Event[] $events
     * @param string[] $withEventTypes
     * @return bool
     */
    public function hasConflict($events, $withEventTypes) {
        foreach ($events as $event) {
            $eventType = $event->type();
            if ( ! isset($this->registry[$eventType])) {
                return true;
            }

            if (array_intersect($this->registry[$eventType], $withEventTypes)) {
                return true;
            }
        }

        return false;
    }

    public function registerConflicts($eventType, $conflictsWithEventTypes) {
        if ( ! isset($this->registry[$eventType])) {
            $this->registry[$eventType] = array();
        }

        $this->registry[$eventType] = array_merge($this->registry[$eventType], $conflictsWithEventTypes);
    }
}