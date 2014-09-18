<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits;

trait EmptyGuardTrait
{
    use \Zend\Stdlib\Guard\EmptyGuardTrait {
        \Zend\Stdlib\Guard\EmptyGuardTrait::guardAgainstEmpty as zendGuardAgainstEmpty;
    }

    /**
     * Verify that the data is not empty
     *
     * @param  mixed      $data           the data to verify
     * @param  string     $dataName       the data name
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardAgainstEmpty(
        $data,
        $dataName = 'Argument',
        $exceptionClass = null
    ) {
        if (null === $exceptionClass) {
            $exceptionClass = '\InvalidArgumentException';
            if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
            }
        }
        $this->zendGuardAgainstEmpty($data, $dataName, $exceptionClass);

        return true;
    }

}
