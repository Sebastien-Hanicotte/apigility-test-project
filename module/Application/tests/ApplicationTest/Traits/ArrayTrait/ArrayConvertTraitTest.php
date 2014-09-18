<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\ArrayTrait;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\ArrayTrait\ArrayConvertTrait;
use Zend\Stdlib\Parameters;

class ImplArrayConvertTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use ArrayConvertTrait;
}

class ArrayConvertTraitTest extends AbstractTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\ArrayTrait\ImplArrayConvertTrait';
    const TRAIT_METHOD_NAME = 'convertToArray';

    protected static $INVALID_PARAM = array(null, false);

    public function testConvertFromArray()
    {
        $array = array(
            'tor',
            'foo' => 'bar',
        );
        $this->assertEquals($array, $this->callProtectedTraitsMethod(array($array)));
    }

    public function testConvertFromTraversable()
    {
        $array = array(
            'tor',
            'foo' => array('bar'),
        );
        $this->assertEquals($array, $this->callProtectedTraitsMethod(array(new Parameters($array), true)));
    }
}
