<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;

use Application\Traits\Fs\RenameTrait;

class ImplRenameTrait
{
    const TRAIT_EXCEPTION_CLASS = AbstractTraitTest::OTHER_EXCEPTION_CLASS;
    use RenameTrait;
}

class RenameTraitTest extends AbstractFsTraitTest
{
    const TRAIT_IMPL_CLASS = 'ApplicationTest\Traits\Fs\ImplRenameTrait';
    const TRAIT_METHOD_NAME = 'rename';

    protected static $INVALID_PARAM = null;

    public function setUp()
    {
        static::$INVALID_PARAM = array(
            $this->rootDir .'/foo.txt',
            $this->rootDir .'/non-writable-dir/foo.txt',
        );
        parent::setUp();
    }

    public function testCopy()
    {
        $this->assertTrue(
            $this->callProtectedTraitsMethod(array(
                $this->rootDir .'/foo.txt',
                $this->rootDir .'/too.txt',
            ))
        );
        $this->assertFileEquals(
            $this->rootDir .'/foo-copy.txt',
            $this->rootDir .'/too.txt'
        );
    }
}
