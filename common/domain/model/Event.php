<?php

namespace connected\common\domain\model;

/**
 * @todo add EventMetadata - like who issued the event
 *
 * Class Event
 */
class Event extends Message {
    private $aggregateId;
    private $createdAt;
    /** @todo add a microtime float */

    /**
     * @param UUID $aggregateId
     */
    public function __construct(UUID $aggregateId) {
        parent::__construct();
        $this->aggregateId = $aggregateId;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return UUID
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return \DateTime
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    // @todo add EventType
    public function type() {
        return UnqualifiedClassName::fromObject($this);
    }
}