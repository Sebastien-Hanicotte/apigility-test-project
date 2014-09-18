<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

trait RenameTrait
{
    protected function rename(
        $source,
        $dest,
        $exceptionClass = null
    ) {
        if (false == @rename($source, $dest)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\RuntimeException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s cannot copy file "%s" to "%s"',
                __METHOD__,
                $source,
                $dest
            ));
        }

        return true;
    }
}
