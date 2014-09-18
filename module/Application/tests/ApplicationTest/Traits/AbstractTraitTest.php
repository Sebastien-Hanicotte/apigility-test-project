<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits;

abstract class AbstractTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Trait name we want to test.
     * @var string
     */
    const TRAIT_NAME = '';

    /**
     * A class name which use the trait
     * @var string
     */
    const TRAIT_IMPL_CLASS = '';

    /**
     * Method name of the trait.
     * @var string
     */
    const TRAIT_METHOD_NAME = '';

    /**
     * Exception class name not used in Trait to test if the trait throw implementation given exception
     * @var string
     */
    const OTHER_EXCEPTION_CLASS = '\UnexpectedValueException';

    /**
     * Exception class name not used in Trait to test if the trait throw parameter given exception
     */
    const OTHER_PARAM_EXCEPTION_CLASS = '\LogicException';

    /**
     * Does the exception of the trait get parameter name.
     * @var boolean
     */
    const GIVE_PARAM_NAME = true;

    /**
     * The param name to give to the trait method
     * @var string
     */
    const PARAM_NAME = 'foobar';

    /**
     * Parameters sample to make the traits failed and emit an exception.
     * @var array
     */
    protected static $INVALID_PARAM = null;

    /**
     * The trait instance.
     * @var object
     */
    protected $trait;

    /**
     * The trait method.
     * @var object
     */
    protected $method;

    /**
     * We create a trait object thanks phpunit, and put protected method accessible thanks reflection
     *
     * @see callProtectedTraitsMethod
     */
    public function setUp()
    {
        $class = static::TRAIT_IMPL_CLASS;
        $this->trait = $class !== ''
                     ? new $class()
                     : $this->getObjectForTrait(static::TRAIT_NAME);

        $reflection = new \ReflectionClass(get_class($this->trait));
        $this->method = $reflection->getMethod(static::TRAIT_METHOD_NAME);
        $this->method->setAccessible(true);
    }

    /**
     * Call protected/private method of a trait.
     *
     * @param  array $parameters Array of parameters to pass into method.
     * @return mixed Method return.
     */
    public function callProtectedTraitsMethod(array $parameters = null)
    {
        return $this->method->invokeArgs($this->trait, $parameters);
    }

    /**
     * Make the trait emit an exception and check for the param name
     */
    public function testMessageContainVariableName()
    {
        if (static::GIVE_PARAM_NAME == false) {
            // ignore this test
            $this->assertTrue(true);

            return;
        }
        $param = static::$INVALID_PARAM;
        $param[] = static::PARAM_NAME;
        try {
            $this->callProtectedTraitsMethod($param);
        } catch (\Exception $e) {
            $this->stringContains(static::PARAM_NAME, $e->getMessage());
        }
    }

    /**
     * Make the traht emit a given exception.
     */
    public function testThrowGivenException()
    {
        if (empty(static::$INVALID_PARAM)) {
            // ignore this test
            $this->assertTrue(true);

            return;
        }
        $param = static::$INVALID_PARAM;
        if (static::GIVE_PARAM_NAME) {
            $param[] = '';
        }
        $param[] = static::OTHER_PARAM_EXCEPTION_CLASS;
        $this->setExpectedException(static::OTHER_PARAM_EXCEPTION_CLASS);
        $this->callProtectedTraitsMethod($param);
    }

    /**
     * Make the trait emit an exception given by constant definition
     */
    public function testThrowGivenConstantException()
    {
        if (static::TRAIT_IMPL_CLASS == '') {
            // ignore the test
            $this->assertTrue(true);

            return;
        }

        $this->setExpectedException(static::OTHER_EXCEPTION_CLASS);
        $this->callProtectedTraitsMethod(static::$INVALID_PARAM);
    }
}
