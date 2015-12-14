<?php

namespace connected\reservation\application\room\command\handler;

use connected\common\domain\model\UUID;
use connected\reservation\application\room\command\ChangeRoomNameCommand;
use connected\reservation\domain\model\room\RoomRepository;

class ChangeRoomNameCommandHandler
{
    private $repository;

    public function __construct(RoomRepository $repository) {
        $this->repository = $repository;
    }

    public function handle(ChangeRoomNameCommand $command) {
        $room = $this->repository->getById(new UUID($command->roomId()));

        if ( ! $room) {
            /** @todo how to handle this exception? */
            throw new \Exception("No room found with id: ".$command->roomId());
        }

        $room->changeName($command->newName());

        $this->repository->save($room, $command->aggregateVersion());
    }
}