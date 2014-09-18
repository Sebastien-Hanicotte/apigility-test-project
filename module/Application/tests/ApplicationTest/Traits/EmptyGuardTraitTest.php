<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits;

use Application\Traits\EmptyGuardTrait;

class ImplEmptyGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use EmptyGuardTrait;
}

class EmptyGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ImplEmptyGuardTrait';
    const TRAIT_METHOD_NAME = 'guardAgainstEmpty';
    const GIVE_PARAM_NAME = true;
    protected static $INVALID_PARAM = array('');

    public function testNonEmpty()
    {
        $var = 'toto';
        $this->assertTrue($this->callProtectedTraitsMethod(array($var)));
        $this->assertTrue(strlen($var) > 0);
    }
}
