<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Traits\ArrayTrait;

trait AllArrayTrait
{
    use ArrayGuardTrait;
    use EmptyArrayGuardTrait;

    use ArrayhasKeyGuardTrait;
    use ArrayConvertTrait;
}
