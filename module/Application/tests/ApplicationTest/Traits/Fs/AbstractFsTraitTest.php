<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Traits\Fs;

use ApplicationTest\Traits\AbstractTraitTest;
use org\bovigo\vfs\vfsStream;

abstract class AbstractFsTraitTest extends AbstractTraitTest
{
    // we never give param name for Fs Traits.
    const GIVE_PARAM_NAME = false;

    protected $rootDir;

    public function setUp()
    {
        vfsStream::setup(
            'tmp',
            null,
            array(
                'foo.txt'           => 'test content',
                'foo-copy.txt'      => 'test content',
                'non-readable.txt'  => '',
                'non-readable-dir'  => array(),
                'non-writable.txt'  => '',
                'non-writable-dir'  => array(),
            )
        );
        $this->rootDir = vfsStream::url('tmp');

        chmod($this->rootDir .'/non-readable.txt', 0222);
        chmod($this->rootDir .'/non-readable-dir', 0222);
        chmod($this->rootDir .'/non-writable.txt', 0444);
        chmod($this->rootDir .'/non-writable-dir', 0444);
        clearstatcache();

        parent::setUp();
    }

    // no test on variable name
    public function testMessageContainVariableName()
    {
        $this->assertTrue(true);
    }
}
