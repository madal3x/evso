<?php

namespace connected\common\domain\model;

/**
 * @todo if the version is greater than what is in the event store it will create gaps in the version
 *
 * Class ConcurrencyAwareCommand
 * @package connected\common\domain\model
 */
abstract class ConcurrencyAwareCommand extends Command
{
    private $aggregateVersion;

    public function __construct($aggregateVersion) {
        $this->aggregateVersion = $aggregateVersion;
    }

    /**
     * @return int
     */
    public function aggregateVersion()
    {
        return $this->aggregateVersion;
    }
}