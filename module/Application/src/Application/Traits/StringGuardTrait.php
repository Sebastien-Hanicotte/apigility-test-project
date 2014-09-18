<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits;

trait StringGuardTrait
{
    /**
     * Verify that the data is a string
     *
     * @param  mixed      $data           the data to verify
     * @param  string     $dataName       the data name
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardForString(
        $data,
        $dataName = 'Argument',
        $exceptionClass = null
    ) {
        if (!is_scalar($data) || !is_string($data)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects %s to be a string',
                __METHOD__,
                $dataName
            ));
        }

        return true;
    }

}
