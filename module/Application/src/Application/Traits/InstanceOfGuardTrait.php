<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits;

trait InstanceOfGuardTrait
{
    /**
     * Verify that the data is an instance of a class name
     *
     * @param  mixed      $object         the object to verify
     * @param  string     $className      FQCN
     * @param  string     $objectName     the object name
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardForInstanceOF(
        $object,
        $className,
        $objectName = 'Argument',
        $exceptionClass = null
    ) {
        if (! $object instanceof $className) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects %s to be an instance of %s ; received %s',
                __METHOD__,
                $objectName,
                $className,
                (is_object($object) ? get_class($object) : gettype($object))
            ));
        }

        return true;
    }

}
