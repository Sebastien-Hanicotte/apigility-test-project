<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

trait ArrayGuardTrait
{
    public function guardForArray(
        $array,
        $arrayName = 'Argument',
        $exceptionClass = null
    ) {
        if (!is_array($array)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects %s to be an array',
                __METHOD__,
                $arrayName
            ));
        }

        return true;
    }
}
