<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

/**
 * Provide method to check if array values repected some contraints
 */
trait ArrayValuesCheckTrait
{
    // ArrayGuardTrait
    abstract protected function guardForArray($array, $arrayName = 'Argument', $exceptionClass = '\InvalidArgument');

    /**
     * Check if a callable return always true on some array values
     *
     * @param  mixed      $array          The array
     * @param  mixed      $keyOrArray     A key or an array of keys
     * @param  callable   $checkCallback  A callable which returns a boolean result
     * @param  string     $arrayName      The name of the array variable
     * @param  string     $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function checkArrayValues(
        $array,
        $keyOrArray,
        $checkCallback = '',
        $arrayName = 'Argument',
        $exceptionClass = null
    ) {
        $this->guardForArray($array, $arrayName, $exceptionClass);

        if (is_scalar($keyOrArray)) {
            $keyOrArray = array($keyOrArray);
        }
        $this->guardForArray($keyOrArray, '$keysOrArray', $exceptionClass);

        if (!is_callable($checkCallback, false, $callableName)) {
            if (null === $exceptionClass) {
                $exceptionClass = '\InvalidArgumentException';
                if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                    $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                }
            }
            throw new $exceptionClass(sprintf(
                '%s expects $checkCallback to be callable',
                __METHOD__
            ));
        }

        foreach ($keyOrArray as $key) {
            try {
                if (!call_user_func($checkCallback, $array[$key])) {
                    // throw Exception to jump to catch statements
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                if (null === $exceptionClass) {
                    $exceptionClass = '\InvalidArgumentException';
                    if (defined('static::TRAIT_EXCEPTION_CLASS')) {
                        $exceptionClass = static::TRAIT_EXCEPTION_CLASS;
                    }
                }
                $dataLabel = sprintf($arrayName, $key);
                throw new $exceptionClass(sprintf(
                    '%s expects %s to be true for %s',
                    __METHOD__,
                    $callableName,
                    $dataLabel
                ));
            }
        }

        return true;
    }
}
