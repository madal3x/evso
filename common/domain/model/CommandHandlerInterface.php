<?php

namespace connected\common\domain\model;

interface CommandHandlerInterface
{
    public function handle(Command $command);
}