<?php

namespace Acquisition\Domain\Entity;

use Application\Traits\ArrayTrait\ArrayGuardTrait;
use Application\Traits\ArrayTrait\ArrayHasKeyGuardTrait;

class JournalEvent extends AbstractEntity
{
    use ArrayGuardTrait;
    use ArrayHasKeyGuardTrait;

    const TRAIT_EXCEPTION_CLASS = 'InvalidArgumentException';

    /*
     * Disabled during suppression minify testing

    protected $minify = array(
        'date' => 'd',
        'ip' => 'i',
        'source' => 's',
        'type' => 't',
        'data' => 'da'
    );

    */

    /**
     * Array of Event Type
     *
     * @var array
     */
    protected $eventType = array(
        1 => array(
            'name' => 'sub',
            'data' => 'Acquisition\Domain\ValueObject\SubData',
        ),
        2 => array(
            'name' => 'unsub',
            'data' => 'Acquisition\Domain\ValueObject\UnsubData',
        ),
        3 => array(
            'name' => 'blacklist',
            'data' => 'Acquisition\Domain\ValueObject\BlackListData',
        ),
    );

    /**
     * Getter for eventType
     *
     * @param  int    $type
     * @return string
     */
    public function getEventType($type)
    {
        $this->guardArrayHasKey($this->eventType, $type);

        return $this->eventType[$type]['data'];
    }

    /**
     * Populate the entity
     *
     * @param array $data
     */
    public function populate(array $data)
    {
        $this->guardArrayHasKey($data, array('type','data'));

        $myClass = $this->getEventType($data['type']);

        $data['data'] = new $myClass($data['data']);

        parent::populate($data);
    }

}
