<?php

namespace Consumption;

use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Traits\ArrayTrait\ArrayHasKeyGuardTrait;
use Application\Traits\ArrayTrait\ArrayGuardTrait;
use Application\Traits\PositiveNumberGuardTrait;

/**
 * Event Service
 */
class EventService
{
    use ArrayGuardTrait;
    use ArrayHasKeyGuardTrait;
    use PositiveNumberGuardTrait;

    /**
     * Constructor
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    protected $event = array(
        1 => "SubEventService",
        2 => "UnsubEventService",
        3 => "BlacklistService"
    );

    /**
     *
     * @param  integer         $type
     * @return SubEventService | UnsubEventService | BlacklistService
     */
    public function getConsumer($type)
    {
        $this->guardForPositiveNumber($type);

        return $this->serviceManager->get($this->getEvent($type));
    }

    /**
     * getter event
     * @param  integer $type
     * @return string
     */
    public function getEvent($type)
    {
        $this->guardArrayHasKey($this->event, $type);

        return $this->event[$type];
    }
}
