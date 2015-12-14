<?php

namespace connected\test;

use connected\common\domain\model\UUID;
use connected\common\event\sourcing\EventSourcedRepository;
use connected\reservation\domain\model\room\Room;
use connected\reservation\port\adapter\persistence\repository\EventSourcedRoomRepository;
use connected\reservation\domain\model\room\RoomRepository;
use connected\common\domain\model\Event;

class RoomTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate() {
        $id = new UUID();
        $name = 'testRoom';
        $seatCapacity = 2;
        $room = Room::create($id, $name, $seatCapacity);

        $this->roomRepositoryWithExpectedEvents($room->uncommittedEvents())->save($room, 0);

        $this->assertEquals($name, $room->name());
        $this->assertEquals($seatCapacity, $room->seatCapacity());
        $this->assertEquals(0, $room->bookedCapacity());

        return $room;
    }

    /**
     * @depends testCreate
     */
    public function testChangeName(Room $room) {
        $newName = "testNewName";

        $room->changeName($newName);

        $this->roomRepositoryWithExpectedEvents($room->uncommittedEvents())->save($room, 1);

        $this->assertEquals($newName, $room->name());

        return $room;
    }

    /**
     * @depends testChangeName
     */
    public function testBookSeat(Room $room) {
        $room->bookSeat();

        $this->roomRepositoryWithExpectedEvents($room->uncommittedEvents())->save($room, 2);

        $this->assertEquals(1, $room->bookedCapacity());

        return $room;
    }

    /**
     * @depends testBookSeat
     */
    public function testOverbook(Room $room) {
        $this->setExpectedException('Exception');

        $room->bookSeat();
        $room->bookSeat();
    }

    /**
     * @param $events Event[]
     * @return RoomRepository
     */
    private function roomRepositoryWithExpectedEvents($events) {
        $eventStore = $this->getMock('\connected\common\event\sourcing\EventStore');
        $eventStore->expects($this->once())->method('saveChanges')->with($events);

        $roomRepository = new EventSourcedRoomRepository(new EventSourcedRepository($eventStore));

        return $roomRepository;
    }
}