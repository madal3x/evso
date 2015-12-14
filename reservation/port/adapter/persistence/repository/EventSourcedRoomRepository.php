<?php

namespace connected\reservation\port\adapter\persistence\repository;

use connected\reservation\domain\model\room\RoomRepository;
use connected\common\event\sourcing\EventSourcedRepository;
use connected\reservation\domain\model\room\Room;
use connected\common\domain\model\UUID;

class EventSourcedRoomRepository implements RoomRepository
{
    private $repository;

    public function __construct(EventSourcedRepository $repository) {
        $this->repository = $repository;
    }

    public function getById(UUID $roomId) {
        return $this->repository->getById($roomId, '\connected\reservation\domain\model\room\Room');
    }

    public function save(Room $room, $originatingVersion) {
        $this->repository->save($room, $originatingVersion);
    }
}