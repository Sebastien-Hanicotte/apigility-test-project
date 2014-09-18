<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

trait EmptyArrayGuardTrait
{
    /**
     * Verify that an array is not empty.
     *
     * @param  mixed      $array          The array
     * @param  string     $dataName       The name of the array variable
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardAgainstEmptyArray(
        $data,
        $dataName = 'Argument',
        $exceptionClass = null
    ) {
        if (!is_array($data) || count($data) < 1) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects %s to be an non-empty array',
                __METHOD__,
                $dataName
            ));
        }

        return true;
    }
}
