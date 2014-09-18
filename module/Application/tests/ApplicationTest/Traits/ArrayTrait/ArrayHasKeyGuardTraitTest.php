<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\ArrayTrait;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\ArrayTrait\ArrayHasKeyGuardTrait;
use Application\Traits\ArrayTrait\ArrayGuardTrait;

trait TestArrayHasKeyGuardTrait
{
    use ArrayGuardTrait;
    use ArrayHasKeyGuardTrait;
}

class ImplArrayHasKeyGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use TestArrayHasKeyGuardTrait;
}

class ArrayHasKeyGuardTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ArrayTrait\ImplArrayHasKeyGuardTrait';
    const TRAIT_METHOD_NAME = 'guardArrayHasKey';

    static protected $INVALID_PARAM = array(
        array(
            'foo'   => true,
            2       => 'bar',
            'tor'   => 1
        ),
        'fff'
    );

    public function testArrayHasKey()
    {
        $array = array(
            'foo'   => true,
            2       => 'bar',
            'tor'   => 1
        );

        $this->assertTrue($this->callProtectedTraitsMethod(array($array, 'foo')));
        $this->assertTrue($this->callProtectedTraitsMethod(array($array, array(2, 'tor'))));
    }
}
