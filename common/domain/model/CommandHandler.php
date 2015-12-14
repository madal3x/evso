<?php

namespace connected\common\domain\model;

use connected\common\domain\model\Command;
use connected\common\domain\model\CommandHandlerFactory;

class CommandHandler
{
    private $commandHandlerFactory;

    public function __construct(CommandHandlerFactory $commandHandlerFactory) {
        $this->commandHandlerFactory = $commandHandlerFactory;
    }

    public function handle(Command $command) {
        //@todo deduplicate commands based on id

        if ( ! $handler = $this->commandHandlerFactory->create($command)) {
            throw new \Exception("There is no handler registered for command: ".get_class($command));
        }

        $handler->handle($command);
    }
}