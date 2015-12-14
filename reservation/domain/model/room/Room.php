<?php

namespace connected\reservation\domain\model\room;

use connected\common\domain\model\EventSourcedAggregateRoot;
use connected\common\domain\model\UUID;
use connected\reservation\domain\model\room\event\RoomCreated;
use connected\reservation\domain\model\room\event\RoomNameChanged;
use connected\reservation\domain\model\room\event\SeatBooked;

class Room extends EventSourcedAggregateRoot {
    private $name;
    private $seatCapacity;
    private $bookedCapacity;

    /**
     * @param UUID $roomId
     * @param $name
     * @param $seatCapacity
     * @return Room
     */
    public static function create(UUID $roomId, $name, $seatCapacity) {
        $room = new self(array());

        $room->applyEvent(new RoomCreated($roomId, $name, $seatCapacity));

        return $room;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    public function changeName($newName) {
        $this->applyEvent(new RoomNameChanged($this->id(), $newName));
    }

    public function bookSeat() {
        if ($this->bookedCapacity == $this->seatCapacity) {
            throw new \Exception("The room is full.");
        }

        $this->applyEvent(new SeatBooked($this->id()));
    }

    /**
     * @return mixed
     */
    public function seatCapacity()
    {
        return $this->seatCapacity;
    }

    /**
     * @return mixed
     */
    public function bookedCapacity()
    {
        return $this->bookedCapacity;
    }

    protected function onSeatBooked(SeatBooked $event) {
        $this->incBookedCapacity();
    }

    protected function onRoomCreated(RoomCreated $event) {
        $this->setId($event->aggregateId());
        $this->setName($event->roomName());
        $this->setSeatCapacity($event->seatCapacity());
        $this->setBookedCapacity(0);
    }

    protected function onRoomNameChanged(RoomNameChanged $event) {
        $this->setName($event->newName());
    }

    private function setName($name)
    {
        //@todo assert

        $this->name = $name;
    }

    private function setSeatCapacity($seatCapacity) {
        //@todo assert

        $this->seatCapacity = $seatCapacity;
    }

    private function setBookedCapacity($bookedCapacity) {
        //@todo assert

        $this->bookedCapacity = $bookedCapacity;
    }

    private function incBookedCapacity() {
        //@todo assert

        $this->bookedCapacity++;
    }
}