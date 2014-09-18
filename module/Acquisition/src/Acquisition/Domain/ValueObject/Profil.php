<?php

namespace Acquisition\Domain\ValueObject;

use Acquisition\Domain\DomainArray;

class Profil extends AbstractValueObject
{
    protected $minify = array(
        'firstname'     => 'f',
        'lastname'      => 'l',
        'birth_date'    => 'bd',
        'email'         => 'e',
        'civility'      => 'c',
        'job'           => 'j',
        'phone'         => 'p',
        'phone_2'        => 'p2',
        'addresses'     => 'a', // Domain/DomainArray[ Domain\ValueObject\Address ]
    );

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        if (isset($data['addresses']) && is_array($data['addresses'])) {
            $adressesArray = array();
            foreach ($data['addresses'] as $value) {
                $adressesArray []= new Address($value);
            }
            $data['addresses'] = new DomainArray($adressesArray);
        }
        parent::populate($data);
    }
}
