<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits;

use Application\Traits\ClassGuardTrait;

class ImplClassGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use ClassGuardTrait;
}

class ClassGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ImplClassGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForClass';
    const GIVE_PARAM_NAME = false;
    protected static $INVALID_PARAM = array('DoesntExistsClass');

    public function testNonEmpty()
    {
        $var = '\Datetime';
        $this->assertTrue($this->callProtectedTraitsMethod(array($var)));
        $this->assertTrue(class_exists($var));
    }
}
