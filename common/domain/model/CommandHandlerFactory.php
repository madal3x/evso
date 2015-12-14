<?php

namespace connected\common\domain\model;

interface CommandHandlerFactory
{
    /**
     * @param Command $command
     * @return CommandHandlerInterface
     */
    public function create(Command $command);
}