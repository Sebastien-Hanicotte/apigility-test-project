<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\FileGetContentsTrait;

class ImplFileGetContentsTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use FileGetContentsTrait;
}

class FileGetContentsTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplFileGetContentsTrait';
    const TRAIT_METHOD_NAME = 'fileGetContents';

    protected static $INVALID_PARAM = array('');

    public function testFileGetContents()
    {
        $this->assertEquals(
            'test content',
            $this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt'))
        );
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
