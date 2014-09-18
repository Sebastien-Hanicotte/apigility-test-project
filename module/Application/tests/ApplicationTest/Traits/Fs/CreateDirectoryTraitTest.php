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
use Application\Traits\Fs\DirectoryGuardTrait;
use Application\Traits\Fs\CreateDirectoryTrait;

trait TestCreateDirectoryTrait
{
    use WritableGuardTrait;
    use DirectoryGuardTrait;
    use CreateDirectoryTrait;
}

class ImplCreateDirectoryTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use TestCreateDirectoryTrait;
}

class CreateDirectoryTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplCreateDirectoryTrait';
    const TRAIT_METHOD_NAME = 'createDirectory';

    protected static $INVALID_PARAM = array('');

    public function testCreateDirectory()
    {
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/createDirectory')));
        $this->assertFileExists($this->rootDir.'/createDirectory');
    }

    public function testExceptionDirectoryNotWritable()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/non-writable-dir'));
    }

    public function testExceptionFileGiven()
    {
        $this->setExpectedException('\RuntimeException');
        $this->callProtectedTraitsMethod(array($this->rootDir .'/foo.txt'));
    }
}
