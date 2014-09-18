<?php

namespace Acquisition;
use Acquisition\Domain\AbstractDomain;

interface ImporterInterface
{
    public function save(AbstractDomain $object);
}
