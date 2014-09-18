<?php

namespace Acquisition\Domain\ValueObject;

use Acquisition\Domain\DomainArray;

class SubData extends AbstractValueObject
{
    /*
     * Disabled during suppression minify testing
     *
    protected $minify = array(
        'profil'        => 'p',
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
        if (isset($data['profil']) && is_array($data['profil'])) {
            $data['profil'] = new Profil($data['profil']);
        }
        parent::populate($data);
    }
}
