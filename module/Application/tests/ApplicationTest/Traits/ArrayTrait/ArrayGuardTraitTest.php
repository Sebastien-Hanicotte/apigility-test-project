<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\ArrayTrait;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\ArrayTrait\ArrayGuardTrait;

class ImplArrayGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use ArrayGuardTrait;
}

class ArrayGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ArrayTrait\ImplArrayGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForArray';

    protected static $INVALID_PARAM = array(3);

    public function testArray()
    {
        $array = array(
            'tor',
            'foo' => 'bar',
        );
        $this->assertTrue($this->callProtectedTraitsMethod(array($array)));
    }
}
