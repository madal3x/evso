<?php

namespace connected\common\domain\model;

abstract class EventSourcedAggregateRoot {
    private $id;
    private $uncommittedEvents;
    private $version;

    /**
     * @param $events Event[]
     */
    public function __construct($events) {
        if ($events) {
            foreach ($events as $event) {
                $this->mutate($event);
            }

            $this->version = count($events);
        } else {
            $this->version = 0;
        }
    }

    /**
     * @return UUID
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function version()
    {
        return $this->version;
    }

    public function uncommittedEvents() {
        return $this->uncommittedEvents;
    }

    /**
     * @param UUID $id
     */
    protected function setId(UUID $id)
    {
        $this->id = $id;
    }

    // append new mutating events and applyEvent mutations
    protected function applyEvent(Event $event) {
        $this->uncommittedEvents[] = $event;

        $this->mutate($event);

        $this->incVersion();
    }

    private function mutate(Event $event) {
        $mutatingMethodName = $this->eventToMutatingMethodName($event);
        if (method_exists($this, $mutatingMethodName)) {
            $this->{$mutatingMethodName}($event);
        }
    }

    /**
     * Aggregates need to implement mutating methods for each event as form on + EventName
     * e.g. onRoomCreated
     *
     * these methods should also be PROTECTED
     *
     * @param Event $event
     * @return string
     */
    private function eventToMutatingMethodName(Event $event) {
        return 'on' . UnqualifiedClassName::fromObject($event);
    }

    private function incVersion() {
        $this->version++;
    }
}