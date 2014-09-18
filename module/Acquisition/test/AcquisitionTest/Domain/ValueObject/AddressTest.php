<?php

namespace AcquisitionTest\Domain\ValueObject;

use AcquisitionTest\Domain\AbstractDomainClassTest;

class AddressTest extends AbstractDomainClassTest
{
    const DOMAIN_CLASS = 'Acquisition\Domain\ValueObject\Address';

    protected $initArray = array(
        'address'          => '3 cour du pavillon de chasse',
        'postal_code'      => '77600',
        'department'       => 'Seine-et-Marne',
        'city'             => 'Bussy Saint Georges',
        'country'          => 'France',
        'address_checked'  => true,
    );

    protected $minifyArray = array(
        'a'  => '3 cour du pavillon de chasse',
        'pc' => '77600',
        'd'  => 'Seine-et-Marne',
        'c'  => 'Bussy Saint Georges',
        'co' => 'France',
        'ac' => true,
    );
}
