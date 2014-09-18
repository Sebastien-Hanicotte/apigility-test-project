<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\ReadableGuardTrait;

class ImplReadableGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use ReadableGuardTrait;
}

class ReadableGuardTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplReadableGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForReadable';

    protected static $INVALID_PARAM = array('');

    public function testReadable()
    {
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt')));
        $this->assertTrue(is_readable($this->rootDir.'/foo.txt'));
    }

    public function testExceptionPathNotExist()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/doesnt-exist'));
    }

    public function testExceptionDirectoryNotReadable()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/non-readable-dir'));
    }

    public function testExceptionFileNotReadable()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/non-readable.txt'));
    }
}
