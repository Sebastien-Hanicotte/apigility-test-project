<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits;

trait ClassGuardTrait
{
    /**
     * Verify that the data is an instance of a class name
     *
     * @param  string     $className      FQCN
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardForClass(
        $className,
        $exceptionClass = null
    ) {
        if (!class_exists($className)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s cannot find class definition %s',
                __METHOD__,
                $className
            ));
        }

        return true;
    }

}
