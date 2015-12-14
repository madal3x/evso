<?php

namespace connected\test;

use connected\common\domain\model\UUID;
use connected\reservation\application\room\command\ChangeRoomNameCommand;
use connected\reservation\application\room\command\handler\ChangeRoomNameCommandHandler;
use connected\reservation\domain\model\room\event\RoomCreated;
use connected\reservation\domain\model\room\Room;

class ChangeRoomNameCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function test() {
        $originatingVersion = 1;
        $newName = 'testNewName';
        $roomId = new UUID();

        $room = $this->getMockBuilder('\connected\reservation\domain\model\room\Room')
            ->setConstructorArgs(array(new RoomCreated($roomId, 'testName', 2)))
            ->getMock();

        $room->expects($this->once())
            ->method('changeName')
            ->with($newName);

        $repository = $this->getMock('\connected\reservation\domain\model\room\RoomRepository');
        $repository->expects($this->at(0))->method('getById')->willReturn($room);
        $repository->expects($this->at(1))->method('save');

        (new ChangeRoomNameCommandHandler($repository))->handle(new ChangeRoomNameCommand($originatingVersion, $roomId->value(), $newName));
    }
}
