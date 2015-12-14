<?php

namespace connected\common\domain\model;

abstract class Message
{
    private $id;

    public function __construct() {
        $this->id = new UUID();
    }

    /**
     * @return UUID
     */
    public function id()
    {
        return $this->id;
    }
}