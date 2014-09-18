<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\ArrayTrait;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\ArrayTrait\EmptyArrayGuardTrait;

class ImplEmptyArrayGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use EmptyArrayGuardTrait;
}

class EmptyArrayGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ArrayTrait\ImplEmptyArrayGuardTrait';
    const TRAIT_METHOD_NAME = 'guardAgainstEmptyArray';

    protected static $INVALID_PARAM = array(array());

    public function testNonEmptyArray()
    {
        $array = array('foo');
        $this->assertTrue($this->callProtectedTraitsMethod(array($array)));
    }
}
