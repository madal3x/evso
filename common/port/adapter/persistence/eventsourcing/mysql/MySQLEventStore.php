<?php

namespace connected\common\port\adapter\persistence\eventsourcing\mysql;

use connected\common\domain\model\UnqualifiedClassName;
use connected\common\domain\model\UUID;
use connected\common\domain\model\Event;
use connected\common\event\sourcing\ConcurrencyException;
use connected\common\event\sourcing\EventStore;

class MySQLEventStore implements EventStore
{
    const VERSION_AGGREGATE_ID_KEY_NAME = "aggregate_id";

    const EVENT_STREAM_VERSION = 1;

    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getEvents(UUID $aggregateId)
    {
        $s = $this->db->prepare(
            "SELECT
                *
            FROM
                events
            WHERE
                aggregate_id = :aggregateId
            AND
                event_stream_version = :eventStreamVersion
            ORDER BY
                version ASC"
        );
        $s->execute(array(
            ':aggregateId' => $aggregateId->value(),
            ':eventStreamVersion' => self::EVENT_STREAM_VERSION
        ));
        $rows = $s->fetchAll();
        $events = array();
        foreach ($rows as $row) {
            $events[] = unserialize($row['data']);
        }

        return $events;
    }

    /**
     * @param Event[] $events
     * @param int $originatingVersion
     * @throws ConcurrencyException
     */
    public function saveChanges($events, $originatingVersion)
    {
        $versionIncrement = 1;
        // @todo use multi inserts
        $this->db->beginTransaction();
        foreach ($events as $event) {
            $aggregateIdValue = $event->aggregateId()->value();
            $s = $this->db->prepare(
                "INSERT INTO
                    events
                    (aggregate_id, version, data, event_stream_version)
                VALUES
                    (?, ?, ?, ?)"
            );
            try {
                $s->execute(array(
                    $aggregateIdValue,
                    ($originatingVersion + $versionIncrement),
                    serialize($event),
                    self::EVENT_STREAM_VERSION
                ));
            } catch (\PDOException $e) {
                $this->db->rollBack();

                if ($this->isConcurrencyException($e)) {
                    throw new ConcurrencyException(
                        $aggregateIdValue,
                        $this->eventsToEventTypes($this->getEventsSinceVersion($aggregateIdValue, $originatingVersion + $versionIncrement)),
                        $e
                    );
                } else {
                    throw($e);
                }
            }

            $versionIncrement++;
        }
        $this->db->commit();
    }

    /**
     * @param \PDOException $e
     * @return bool
     */
    private function isConcurrencyException(\PDOException $e) {
        return $e->getCode() === "23000" && strpos($e->getMessage(), self::VERSION_AGGREGATE_ID_KEY_NAME);
    }

    /**
     * @param $events Event[]
     * @return string[]
     */
    private static function eventsToEventTypes($events) {
        return array_map(function($event){return UnqualifiedClassName::fromObject($event);}, $events);
    }

    /**
     * @param $aggregateIdValue string
     * @param $version int
     * @return Event[]
     */
    private function getEventsSinceVersion($aggregateIdValue, $version)
    {
        $s = $this->db->prepare(
            "SELECT
                *
            FROM
                events
            WHERE
                aggregate_id = :aggregateId
            AND
                version >= :version
            ORDER BY
                version ASC"
        );
        $s->execute(array(
            ':aggregateId' => $aggregateIdValue,
            ':version' => $version
        ));
        $rows = $s->fetchAll();
        $events = array();
        foreach ($rows as $row) {
            $events[] = unserialize($row['data']);
        }

        return $events;
    }
}