<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

use Zend\Stdlib\ArrayUtils;

/**
 * provide a convertion method to array
 */
trait ArrayConvertTrait
{
    /**
     * Convert data into array
     *
     * @param  mixed      $data           the data
     * @param  string     $dataName       the data name
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function convertToArray(
        $data,
        $reccursive = true,
        $dataName = 'Argument',
        $exceptionClass = null
    ) {
        try {
            return ArrayUtils::iteratorToArray($data, $reccursive);
        } catch (\Exception $e) {
            if (null === $exceptionClass) {
                $exceptionClass = '\RuntimeException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s cannot convert %s into array',
                __METHOD__,
                $dataName
            ));
        }
    }
}
