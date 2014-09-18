<?php

namespace Acquisition\Domain\ValueObject;

use Acquisition\Domain\DomainArray;

class UnsubData extends AbstractValueObject
{
    /*
     * Disabled during suppression minify testing
     *
    protected $minify = array(
        'email'         => 'e',
        'civility'      => 'c',
        'destination'   => 'de'
    );
     *
     */

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        if (isset($data['destination']) && is_array($data['destination'])) {
            $data['destination'] = new DomainArray($data['destination']);
        }
        parent::populate($data);
    }
}
