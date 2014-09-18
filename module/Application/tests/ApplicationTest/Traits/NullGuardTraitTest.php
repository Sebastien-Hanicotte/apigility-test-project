<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits;

use Application\Traits\NullGuardTrait;

class ImplNullGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use NullGuardTrait;
}

class NullGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ImplNullGuardTrait';
    const TRAIT_METHOD_NAME = 'guardAgainstNull';
    const GIVE_PARAM_NAME = true;
    protected static $INVALID_PARAM = array(null);

    public function testNonEmpty()
    {
        $var = 'toto';
        $this->assertTrue($this->callProtectedTraitsMethod(array($var)));
        $this->assertTrue(strlen($var) > 0);
    }
}
