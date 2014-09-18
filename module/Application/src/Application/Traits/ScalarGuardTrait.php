<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits;

trait ScalarGuardTrait
{
    public function guardForScalar(
        $scalar,
        $scalarName = 'Argument',
        $exceptionClass = null
    ) {
        if (!is_scalar($scalar)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects %s to be a scalar',
                __METHOD__,
                $scalarName
            ));
        }

        return true;
    }
}
