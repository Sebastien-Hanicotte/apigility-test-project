<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

trait ArrayGetValueTrait
{
    /**
     * Extract an array value, or return a default value.
     *
     * @param mixed $array
     * @param mixed $key
     * @param mixed $default
     */
    protected function getArrayValue(
        $array,
        $key,
        $default = null
    ) {
        return isset($array[$key]) ? $array[$key] : $default;
    }

}
