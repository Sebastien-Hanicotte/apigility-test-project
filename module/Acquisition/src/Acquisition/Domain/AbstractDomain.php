<?php

namespace Acquisition\Domain;

use Zend\Stdlib\ArrayObject as PhpArrayObject;
use Application\Traits\ArrayTrait\ArrayConvertTrait;
use Acquisition\DomainRepository\RepositoryManager;

/**
 *
 * AbstractDomain(
 *      key => value,
 *      key => value,
 *      key => AbstractDomain(
 *          key => AbstractDomain(
 *          ),
 *          key => value,
 *      )
 * )
 */
abstract class AbstractDomain extends PhpArrayObject
{
    use ArrayConvertTrait;

    const SECURE_WRITE_WITH_MINIFY = true;

    protected $minify = array(
            // 'long-key' => 'short-key',
    );

    /**
     * Constructor.
     * @param mixed $initialValue
     */
    public function __construct($initialValue = null)
    {
        /**
         * Those checks are made in Unit Test !!!
         * @see AcquisitionTest\Domain\AbstractDomainClassTest
         */
        // check that minify is OK first !
        // if (!empty($this->minify)) {
        //     $maxify = array_flip($this->minify);
        //     $maxifyCount = count($maxify);
        //     $minifyCount = count($this->minify);
        //     if (count(array_merge($this->minify, $maxify)) != $maxifyCount + $minifyCount
        //         || $minifyCount != $maxifyCount
        //     ) {
        //         throw new \RuntimeException("Problème avec maxify");
        //     }
        // }
        // Construct Zend\Stdlib\ArrayObject to have storage space
        parent::__construct();

        $this->initMinify();

        // populate storage if we have data
        if (null !== $initialValue) {
            $this->populate($this->convertToArray($initialValue, false));
        }
    }

    /**
     * initialisation du minify depuis la BDD
     */
    public function initMinify()
    {
        if (empty($this->minify)) {
            $m = new \MongoClient();
            $this->dbTest = $m->test;
            $collection = $this->dbTest->minify;

            $repository = new RepositoryManager($collection);
            $class = strtolower(substr(strrchr(get_called_class(), '\\'), 1));
            $this->minify = $repository->getMinify($class);
        }

        if (array_key_exists('id',$this->minify)) {
            $m = new \MongoClient();
            $this->dbTest = $m->test;
            $collection = $this->dbTest->minify;

            $repository = new RepositoryManager($collection);
            $class = strtolower(substr(strrchr(get_called_class(), '\\'), 1));
            $this->minify = $repository->getMinify($class);
            $this->minify['id'] = '_id';
        }

        return $this->minify;
    }

    /**
     * Populate the object with given array values.
     *
     * @param array $inputArray
     */
    public function populate(array $inputArray)
    {
        foreach ($inputArray as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * When SECURE_WRITE_WITH_MINIY is true, the only acceptable value type are null, scalar and AbstractDomain
     */
    public function offsetSet($key, $value)
    {
        if (static::SECURE_WRITE_WITH_MINIFY) {
            if (!array_key_exists($key, $this->minify)) {
                // ignore write to unknown keys
                return;
            }
        }

        // we keep null field, but check non-null field value
        if ($value !== null) {
            if (!is_scalar($value) && !$value instanceof AbstractDomain && !$value instanceof \MongoDate && !$value instanceof \MongoInt32
            ) {
                throw new \InvalidArgumentException(sprintf(
                        '%s expects $inputArray[%s] to be scalar or instance of AbstractDomain %s received', __METHOD__, $key, (is_object($value) ? get_class($value) : gettype($value))
                ));
            }
        }

        parent::offsetSet($key, $value);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach (array_keys($this->minify) as $key) {
            $value = $this[$key];
            if ($value instanceof self) {
                $value = $value->toArray();
            }
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Populate the object with given minify array.
     *
     * Same as populate(), but field keys are minified
     *
     * @param array $inputArray
     */
    public function mongoPopulate(array $inputArray)
    {
        $this->populate($this->getMaxify($inputArray));
    }

    /**
     * Same as toArray(), but field keys are minified
     */
    public function toMongoArray()
    {
        return $this->getMinify();
    }

    /**
     * Internal method to refactor getMinify() and getMaxify()
     *
     * It translate field to minified version or maxified version. It supports nested AbstractDomain
     * and iterate recursivly.
     *
     * @param  array  $inputArray
     * @param  array  $translateArray
     * @param  string $method
     * @return array
     */
    final private function translate(array $inputArray = null, array $translateArray = null, $method = '')
    {
        if ($inputArray == null) {
            $inputArray = $this->getArrayCopy();
        }

        $result = array();
        foreach ($inputArray as $key => $value) {
            if ($value instanceof self) {
                $value = $value->{$method}();
            }
            if (array_key_exists($key, $translateArray)) {
                $key = $translateArray[$key];
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Return data with minified fiel keys.
     *
     * @param  array  $inputArray
     * @param  array  $translateArray
     * @param  string $method
     * @return array
     */
    public function getMinify(array $inputArray = null, array $translateArray = null)
    {
        if ($translateArray == null) {
            $translateArray = $this->minify;
        }

        return $this->translate($inputArray, $translateArray, 'getMinify');
    }

    /**
     * Return data with maxified fiel keys.
     *
     * @param  array  $inputArray
     * @param  array  $translateArray
     * @param  string $method
     * @return array
     */
    public function getMaxify(array $inputArray = null, array $translateArray = null)
    {
        if ($translateArray == null) {
            $translateArray = array_flip($this->minify);
        }

        return $this->translate($inputArray, $translateArray, 'getMaxify');
    }

}
