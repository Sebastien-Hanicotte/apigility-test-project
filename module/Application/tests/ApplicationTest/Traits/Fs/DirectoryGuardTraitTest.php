<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\DirectoryGuardTrait;

class ImplDirectoryGuardTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use DirectoryGuardTrait;
}

class DirectoryGuardTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplDirectoryGuardTrait';
    const TRAIT_METHOD_NAME = 'guardForDirectory';

    protected static $INVALID_PARAM = array('');

    public function testDirectory()
    {
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir)));
        $this->assertTrue(is_dir($this->rootDir));
    }

    public function testExceptionForAFile()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt'));
    }

    public function testExceptionForNonExistingDirectory()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/foo'));
    }
}
