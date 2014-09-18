<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\FileGuardTrait;

class ImplFileGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use FileGuardTrait;
}

class FileGuardTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplFileGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForFile';

    protected static $INVALID_PARAM = array('');

    public function testDirectory()
    {
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt')));
        $this->assertTrue(is_file($this->rootDir.'/foo.txt'));
    }

    public function testExceptionForADirectory()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir));
    }

    public function testExceptionForNonExistingFile()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/doesnt-exist.txt'));
    }
}
