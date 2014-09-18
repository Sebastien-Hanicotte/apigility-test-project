<?php

namespace Acquisition\Validator;
use Zend\Validator\AbstractValidator;

/**
 * CheckDestination Validator
 */
class CheckDestination extends AbstractValidator
{
    const destination_not_found = "destination_not_found";
    const destination_not_array = "destination_not_array";
    const destination_is_empty = "destination_is_empty";

    protected $messageTemplates = array(
        self::destination_not_array => "'%value%' is not an array",
        self::destination_not_found => "'%value%' is not a good destination",
        self::destination_is_empty => "the array is empty"

    );

    /**
     * Check if data is valid
     * @param  array   $value
     * @return boolean
     */
    public function isValid($value)
    {
        $destination = array("C1", "C2", "C3", "all");

        if (!is_array($value)) {
            $this->error(self::destination_not_array);

            return FALSE;
        }

        if (count($value) == 0) {
            $this->error(self::destination_is_empty);

            return FALSE;
        }

        if (count(array_diff($value, $destination)) !== 0) {
            $this->error(self::destination_not_found);

            return FALSE;
        }

        return TRUE;
    }
}
