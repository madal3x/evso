<?php

namespace connected\reservation\application\room\command\handler;

use connected\common\domain\model\UUID;
use connected\reservation\domain\model\room\RoomRepository;
use connected\reservation\application\room\command\BookSeatCommand;

class BookSeatCommandHandler
{
    private $repository;

    public function __construct(RoomRepository $repository) {
        $this->repository = $repository;
    }

    public function handle(BookSeatCommand $command) {
        $room = $this->repository->getById(new UUID($command->roomId()));

        if ( ! $room) {
            /** @todo how to handle this exception? and aggregate errors? */
            throw new \Exception("No room found with id: ".$command->roomId());
        }

        $room->bookSeat();

        $this->repository->save($room, $command->aggregateVersion());
    }
}