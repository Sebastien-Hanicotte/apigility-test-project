<?php
namespace Acquisition\Domain\Entity;

use Acquisition\Domain\AbstractDomain;

abstract class AbstractEntity extends AbstractDomain
{
    protected $rules;
    protected $date;

    /**
     * Ensure $this->minify has the id field
     * @param mixed $initialArray
     */
    public function __construct($initialArray)
    {
        $this->minify['id'] = '_id';
        parent::__construct($initialArray);

        // we always have an id set to a value or set to null
        if (!isset($this['id'])) {
            $this['id'] = null;
        }
    }

}
