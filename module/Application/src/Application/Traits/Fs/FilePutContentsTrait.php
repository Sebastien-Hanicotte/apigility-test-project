<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

trait FilePutContentsTrait
{
    protected function filePutContents(
        $file,
        $content,
        $mode = 0,
        $exceptionClass = null
    ) {
        if (false == @file_put_contents($file, $content, $mode)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\RuntimeException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s cannot put content into file "%s"',
                __METHOD__,
                $file
            ));
        }

        return true;
    }

    protected function fileAppendContents(
        $file,
        $content,
        $exceptionClass = null
    ) {
        return $this->filePutContents($file, $content, FILE_APPEND, $exceptionClass);
    }
}
