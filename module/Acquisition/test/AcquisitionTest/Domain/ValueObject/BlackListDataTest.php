<?php

namespace AcquisitionTest\Domain\ValueObject;

use AcquisitionTest\Domain\AbstractDomainClassTest;

class BlackListDataTest extends AbstractDomainClassTest
{
    const DOMAIN_CLASS = 'Acquisition\Domain\ValueObject\BlackListData';

    protected $initArray = array(
        'email'         => 'test@prismamedia.com',
        'comment'       => 'we have a problem here !'
    );

    protected $minifyArray = array(
        'e'         => 'test@prismamedia.com',
        'c'         => 'we have a problem here !'
    );
}
