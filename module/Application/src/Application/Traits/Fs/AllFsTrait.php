<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\Fs;

trait AllFsTrait
{
    use FileGuardTrait;
    use DirectoryGuardTrait;
    use ReadableGuardTrait;
    use WritableGuardTrait;

    use CopyFileTrait;
    use RenameTrait;
    use CreateDirectoryTrait;

    use FileGetContentsTrait;
    use FilePutContentsTrait;
}
