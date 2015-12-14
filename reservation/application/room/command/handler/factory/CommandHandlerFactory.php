<?php

namespace connected\reservation\application\room\command\handler\factory;

use connected\common\domain\model\Command;
use connected\common\domain\model\CommandHandlerInterface;
use connected\common\event\sourcing\ConcurrencyConflictResolver;
use connected\common\event\sourcing\EventSourcedRepository;
use connected\common\event\sourcing\OptimisticEventStore;
use connected\common\port\adapter\persistence\eventsourcing\mysql\MySQLEventStore;
use connected\common\port\adapter\persistence\MySQLConnectionFactory;
use connected\reservation\application\room\command\BookSeatCommand;
use connected\reservation\application\room\command\ChangeRoomNameCommand;
use connected\reservation\application\room\command\CreateRoomCommand;
use connected\reservation\application\room\command\handler\BookSeatCommandHandler;
use connected\reservation\application\room\command\handler\ChangeRoomNameCommandHandler;
use connected\reservation\application\room\command\handler\CreateRoomCommandHandler;
use connected\reservation\port\adapter\persistence\repository\EventSourcedRoomRepository;

class CommandHandlerFactory implements \connected\common\domain\model\CommandHandlerFactory
{
    /**
     * @param Command $command
     * @return CommandHandlerInterface
     */
    public function create(Command $command)
    {
        $conflictResolver = new ConcurrencyConflictResolver();
        $conflictResolver->registerConflicts('SeatBooked', array('SeatBooked'));
        // @todo it can't be that other events happen before RoomCreated, or can it?
        $conflictResolver->registerConflicts('RoomCreated', array());
        $conflictResolver->registerConflicts('RoomNameChanged', array());

        if ($command instanceof BookSeatCommand) {
            return new BookSeatCommandHandler(
                new EventSourcedRoomRepository(
                    new EventSourcedRepository(
                        new OptimisticEventStore(new MySQLEventStore(MySQLConnectionFactory::create()), $conflictResolver)
                    )
                )
            );
        } elseif ($command instanceof CreateRoomCommand) {
            return new CreateRoomCommandHandler(
                new EventSourcedRoomRepository(
                    new EventSourcedRepository(
                        new OptimisticEventStore(new MySQLEventStore(MySQLConnectionFactory::create()), $conflictResolver)
                    )
                )
            );
        } elseif ($command instanceof ChangeRoomNameCommand) {
            return new ChangeRoomNameCommandHandler(
                new EventSourcedRoomRepository(
                    new EventSourcedRepository(
                        new OptimisticEventStore(new MySQLEventStore(MySQLConnectionFactory::create()), $conflictResolver)
                    )
                )
            );
        }

        return false;
    }
}