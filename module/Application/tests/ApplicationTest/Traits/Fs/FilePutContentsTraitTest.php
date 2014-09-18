<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\FilePutContentsTrait;

class ImplFilePutContentsTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use FilePutContentsTrait;
}

class FilePutContentsTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplFilePutContentsTrait';
    const TRAIT_METHOD_NAME = 'filePutContents';

    protected static $INVALID_PARAM = null;

    public function setUp()
    {
        static::$INVALID_PARAM = array(
            $this->rootDir .'/non-writable.txt',
            'cannot write',
            0
        );
        parent::setUp();
    }

    public function testFilePutContents()
    {
        $putContent = 'put content';

        $this->assertStringEqualsFile(
            $this->rootDir.'/foo.txt',
            'test content'
        );
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt', $putContent)));
        $this->assertStringEqualsFile(
            $this->rootDir.'/foo.txt',
            $putContent
        );

        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/foo-new.txt', $putContent)));
        $this->assertStringEqualsFile(
            $this->rootDir.'/foo-new.txt',
            $putContent
        );
    }

    public function testFilePutContentsWithMode()
    {
        $putContent = 'put content';

        $this->assertStringEqualsFile(
            $this->rootDir.'/foo.txt',
            'test content'
        );
        $this->assertTrue($this->callProtectedTraitsMethod(array($this->rootDir.'/foo.txt', $putContent, FILE_APPEND)));
        $this->assertStringEqualsFile(
            $this->rootDir.'/foo.txt',
            'test content'.$putContent
        );
    }

    public function testFilePutContentsAppend()
    {
        $putContent = 'put content';

        $this->assertStringEqualsFile(
            $this->rootDir.'/foo.txt',
            'test content'
        );

        $implTrait = new ImplFilePutContentsTrait();
        $reflection = new \ReflectionClass(get_class($implTrait));
        $method = $reflection->getMethod('fileAppendContents');
        $method->setAccessible(true);

        $this->assertTrue($method->invokeArgs(
            $implTrait,
            array($this->rootDir.'/foo.txt', $putContent)
        ));
        $this->assertStringEqualsFile(
            $this->rootDir.'/foo.txt',
            'test content'.$putContent
        );
    }
}
