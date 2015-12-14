<?php

namespace connected\reservation\application\room\command\handler;

use connected\common\domain\model\UUID;
use connected\reservation\application\room\command\CreateRoomCommand;
use connected\reservation\domain\model\room\Room;
use connected\reservation\domain\model\room\RoomRepository;

class CreateRoomCommandHandler
{
    private $repository;

    public function __construct(RoomRepository $repository) {
        $this->repository = $repository;
    }

    public function handle(CreateRoomCommand $command) {
        $room = Room::create(new UUID($command->roomId()), $command->name(), $command->seatCapacity());

        $this->repository->save($room, $command->aggregateVersion());
    }
}