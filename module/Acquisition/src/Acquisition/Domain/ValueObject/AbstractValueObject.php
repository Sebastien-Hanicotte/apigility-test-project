<?php

namespace Acquisition\Domain\ValueObject;

use Acquisition\Domain\AbstractDomain;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

abstract class AbstractValueObject extends AbstractDomain implements InputFilterAwareInterface
{

    /**
     * Tell if we can modify the object
     *
     * Must be true just after the constructor of the object.
     * As the object is not lock during construction, populate() works.
     *
     * @var boolean
     */
    private $isLock = false;

    /**
     * Call parent constructor and lock the content of the array object
     * @param mixed $initialArray
     */
    public function __construct($initalArray = null)
    {
        parent::__construct($initalArray);
        $this->lock();
    }

    /**
     * Lock the content of this array object
     */
    protected function lock()
    {
        $this->isLock = true;
    }

    /**
     * Override the offsetSet method to forbid modification of the object content
     * when object is locked (after construction)
     *
     * @param  mixed             $key
     * @param  mixed             $value
     * @throws \RuntimeException
     */
    public function offsetSet($key, $value)
    {
        if ($this->isLock) {
            throw new \RuntimeException('Cannont modify an ValueObject');
        }
        parent::offsetSet($key, $value);
    }

    /**
     * @param  object    $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \RuntimeException("Not used");
    }

    /**
     * @return object
     */
    public function getInputFilter()
    {
        throw new \RuntimeException('Not implemented');
    }

}
