<?php

namespace AcquisitionTest\Domain\ValueObject;

use AcquisitionTest\Domain\AbstractDomainClassTest;

class UnsubDataTest extends AbstractDomainClassTest
{
    const DOMAIN_CLASS = 'Acquisition\Domain\ValueObject\UnsubData';

    protected $initArray = array(
        'destination' => array('C1', 'C2'),
        'email'         => 'pguiran@prismamedia.com',
        'civility'      => 'M',
    );

    protected $minifyArray = array(
        'de' => array('C1', 'C2'),
        'e'  => 'pguiran@prismamedia.com',
        'c'  => 'M',
    );
}
