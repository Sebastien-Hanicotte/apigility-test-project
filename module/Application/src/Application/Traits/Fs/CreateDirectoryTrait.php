<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

/**
 * Provite a method to create a directory and make all checks
 */
trait CreateDirectoryTrait
{
    // DirectoryGuardTrait
    abstract protected function guardForDirectory($directory, $exceptionClass = null);
    // WritableGuardTrait
    abstract protected function guardForWritable($directory, $exceptionClass = null);

    /**
     * Create the directory making all check
     *
     * @param  mixed      $directory      the directory to create
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function createDirectory(
        $directory,
        $exceptionClass = null
    ) {
        if (!file_exists($directory)) {
            if (false === @mkdir($directory, 0744, true)) {
                if (null === $exceptionClass) {
                    $exceptionClass = '\RuntimeException';
                    if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                        $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                    }
                }
                throw new $exceptionClass(sprintf(
                    '%s cannot create directory "%s"',
                    __METHOD__,
                    $directory
                ));
            }
        }

        $this->guardForDirectory($directory, $exceptionClass);
        $this->guardForWritable($directory, $exceptionClass);

        return true;
    }
}
