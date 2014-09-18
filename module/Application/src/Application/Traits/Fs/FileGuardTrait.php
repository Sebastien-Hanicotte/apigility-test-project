<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

/**
 * Provide a method to check if a path exists and is a file
 */
trait FileGuardTrait
{
    /**
     * Check if a path exists and is a file
     *
     * @param  mixed      $path           the path
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardForFile(
        $path,
        $exceptionClass = null
    ) {
        if (!@file_exists($path) || !@is_file($path)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\RuntimeException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects "%s" to be a file',
                __METHOD__,
                $path
            ));
        }

        return true;
    }
}
