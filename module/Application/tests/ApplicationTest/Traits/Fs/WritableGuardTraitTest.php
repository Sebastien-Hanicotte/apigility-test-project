<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\WritableGuardTrait;

class ImplWritableGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use WritableGuardTrait;
}

class WritableGuardTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplWritableGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForWritable';

    protected static $INVALID_PARAM = array('');

    public function testWritable()
    {
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt')));
        $this->assertTrue(is_writable($this->rootDir.'/foo.txt'));
    }

    public function testExceptionPathNotExist()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/doesnt-exist'));
    }

    public function testExceptionDirectoryNotWritable()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/non-writable-dir'));
    }

    public function testExceptionFileNotWritable()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/non-writable.txt'));
    }
}
