<?php

namespace AcquisitionTest\Domain;

abstract class AbstractEntityClassTest extends AbstractDomainClassTest
{
    public function setUp()
    {
        $this->initArray['id'] = null;
        $this->minifyArray['_id'] = null;
        parent::setUp();
    }
}
