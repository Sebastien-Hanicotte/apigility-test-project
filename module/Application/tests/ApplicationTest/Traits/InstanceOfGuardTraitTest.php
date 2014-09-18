<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits;

use Application\Traits\InstanceOfGuardTrait;

class ImplInstanceOfGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use InstanceOfGuardTrait;
}

class InstanceOfGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ImplInstanceOfGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForInstanceOf';
    const GIVE_PARAM_NAME = true;
    protected static $INVALID_PARAM;

    public function setUp()
    {
        static::$INVALID_PARAM = array(new \Datetime(), 'DoesntExistsClass');
        parent::setUp();
    }

    public function testNonEmpty()
    {
        $obj = new ImplInstanceOfGuardTrait();
        $this->assertTrue($this->callProtectedTraitsMethod(array($obj, 'ApplicationTest\Traits\ImplInstanceOfGuardTrait')));
        $this->assertInstanceOf('ApplicationTest\Traits\ImplInstanceOfGuardTrait', $obj);
    }
}
