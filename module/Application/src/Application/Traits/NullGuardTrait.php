<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits;

/**
 * Provide a guard method against null data
 */
trait NullGuardTrait
{
    use \Zend\Stdlib\Guard\NullGuardTrait {
        \Zend\Stdlib\Guard\NullGuardTrait::guardAgainstNull as zendGuardAgainstNull;
    }

    /**
     * Verify that the data is not null
     *
     * @param  mixed      $data           the data to verify
     * @param  string     $dataName       the data name
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardAgainstNull(
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
        $this->zendGuardAgainstNull($data, $dataName, $exceptionClass);

        return true;
    }
}
