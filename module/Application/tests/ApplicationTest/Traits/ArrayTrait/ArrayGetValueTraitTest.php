<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\ArrayTrait;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\ArrayTrait\ArrayGetValueTrait;

class ArrayGetValueTraitTest extends AbstractTraitTest
{
    const TRAIT_NAME = 'Application\Traits\ArrayTrait\ArrayGetValueTrait';
    const TRAIT_METHOD_NAME = 'getArrayValue';

    public function testGetValue()
    {
        $array = array(
            'tor',
            'foo' => 'bar',
        );
        $this->assertEquals('tor', $this->callProtectedTraitsMethod(array($array, 0)));
        $this->assertEquals('bar', $this->callProtectedTraitsMethod(array($array, 'foo')));
        $this->assertEquals('foo', $this->callProtectedTraitsMethod(array($array, 'bar', 'foo')));
    }
}
