<?php

namespace Acquisition\Domain\ValueObject;

class BlackListData extends AbstractValueObject
{

    protected $minify = array(
        'email'        => 'e',
        'comment'      => 'c'
    );

}
