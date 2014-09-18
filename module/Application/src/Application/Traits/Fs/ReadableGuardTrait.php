<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

/**
 * Provide a method to check if a file or a directory exists and is readable
 */
trait ReadableGuardTrait
{
    /**
     * Check if a file or a directory exists and is readable
     *
     * @param  mixed      $fileOrDirectory the file or directory path
     * @param  string     $exceptionClass  FQCN for the exception
     * @throws \Exception
     */
    protected function guardForReadable(
        $fileOrDirectory,
        $exceptionClass = null
    ) {
        if (!@file_exists($fileOrDirectory) || !@is_readable($fileOrDirectory)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\RuntimeException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s cannot read "%s"',
                __METHOD__,
                $fileOrDirectory
            ));
        }

        return true;
    }
}
