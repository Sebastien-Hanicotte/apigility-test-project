<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

/**
 * Provide method to check if an array has a key (or several keys).
 */
trait ArrayHasKeyGuardTrait
{
    // ArrayGuardTrait
    abstract protected function guardForArray($array, $arrayName = 'Argument', $exceptionClass = '\InvalidArgument');

    /**
     * Check if an array has a key (or several keys)
     *
     * @param  mixed      $array          The array
     * @param  mixed      $keyOrArray     A key or an array of keys
     * @param  string     $arrayName      The name of the array variable
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardArrayHasKey(
        $array,
        $keyOrArray,
        $arrayName = 'Argument',
        $exceptionClass = null
    ) {
        $this->guardForArray($array, $arrayName, $exceptionClass);

        if (is_scalar($keyOrArray)) {
            $keyOrArray = array($keyOrArray);
        }

        $this->guardForArray($keyOrArray, '$keysOrArray', $exceptionClass);

        foreach ($keyOrArray as $key) {
            if (!isset($array[$key])) {
                if (null === $exceptionClass) {
                    $exceptionClass = '\InvalidArgumentException';
                    if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                        $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                    }
                }
                $dataLabel = $arrayName.'["'.$key.'"]';
                throw new $exceptionClass(sprintf(
                    '%s expects %s to be set',
                    __METHOD__,
                    $dataLabel
                ));
            }
        }

        return true;
    }
}
