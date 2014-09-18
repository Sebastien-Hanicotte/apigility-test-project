<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\ArrayTrait;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\ArrayTrait\ArrayValuesCheckTrait;
use Application\Traits\ArrayTrait\ArrayGuardTrait;

trait TestArrayValuesCheckTrait
{
    use ArrayGuardTrait;
    use ArrayValuesCheckTrait;
}

class ImplArrayValuesCheckTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use TestArrayValuesCheckTrait;
}

class ArrayValuesCheckTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ArrayTrait\ImplArrayValuesCheckTrait';
    const TRAIT_METHOD_NAME = 'checkArrayValues';

    static protected $INVALID_PARAM = array(
        array(
            2       => 'bar',
            'tor'   => 2,
        ),
        array(2),
        'is_integer'
    );

    public function testCheckArrayValues()
    {
        $array = array(
            'foo'   => true,
            2       => 'bar',
            'tor'   => 1
        );

        $this->assertTrue(
            $this->callProtectedTraitsMethod(array($array, 'foo', function ($value) { return $value == true; }))
        );
        $this->assertTrue(
            $this->callProtectedTraitsMethod(array($array, 'tor', 'is_integer'))
        );
    }

    public function testExceptionNotCallable()
    {
        $array = array(
            'foo'   => true,
            2       => 'bar',
            'tor'   => 1
        );

        $this->setExpectedException(AbstractTraitTest::OTHER_EXCEPTION_CLASS);
        $this->callProtectedTraitsMethod(array($array, 'foo', 'isset'));
    }
}
