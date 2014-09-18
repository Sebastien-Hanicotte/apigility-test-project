<?php
namespace Acquisition\Domain;

class DomainArray extends AbstractDomain
{
    const SECURE_WRITE_WITH_MINIFY = false;

    /**
     * populate the object with given array values
     *
     * @param array $inputArray
     */
    public function populate(array $inputArray)
    {
        $count = 0;
        foreach ($inputArray as $key => $value) {

            if (!is_scalar($value) && ! $value instanceof AbstractDomain) {
                throw new \InvalidArgumentException(sprintf(
                    '%s expects $inputArray[%s] to be scalar or instance of AbstractDomain %s received',
                    __METHOD__,
                    $key,
                    (is_object($value) ? get_class($value) : gettype($value))
                ));
            }
            $this->offsetSet($count++, $value);
        }
    }

    /**
     * return an array
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $value) {
            if ($value instanceof AbstractDomain) {
                $value = $value->toArray();
            }
            $result []= $value;
        }

        return $result;
    }
}
