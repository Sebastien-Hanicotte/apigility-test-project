<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

trait FileGetContentsTrait
{
    protected function fileGetContents(
        $file,
        $exceptionClass = null
    ) {
        $content = @file_get_contents($file);
        if (false == $content) {
            if (null === $exceptionClass) {
                $exceptionClass = '\RuntimeException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s cannot get content from file "%s"',
                __METHOD__,
                $file
            ));
        }

        return $content;
    }
}
