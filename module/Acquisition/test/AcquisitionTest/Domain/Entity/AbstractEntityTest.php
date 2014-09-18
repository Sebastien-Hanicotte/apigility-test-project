<?php

namespace AcquisitionTest\Domain\Entity;

use Acquisition\Domain\Entity\AbstractEntity;

class ImplAbstractEntity extends AbstractEntity
{
    protected $minify = array(
        'test' => 't',
    );
}

class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructAddId()
    {
        $initArray = array(
            'test' => 'value',
        );
        $minifyArray = array(
            't'  => 'value',
        );
        $domain = new ImplAbstractEntity($initArray);

        $initArray['id'] = $minifyArray['_id'] = null;
        $this->assertEquals($initArray, $domain->toArray());
        $this->assertEquals($initArray, $domain->getMaxify());
        $this->assertEquals($minifyArray, $domain->getMinify());
    }

    public function testConstructWithId()
    {
        $initArray = array(
            'id'   => 'fsdhljredewd',
            'test' => 'value',
        );
        $minifyArray = array(
            '_id' => 'fsdhljredewd',
            't'   => 'value',
        );
        $domain = new ImplAbstractEntity($initArray);

        $this->assertEquals($initArray, $domain->toArray());
        $this->assertEquals($initArray, $domain->getMaxify());
        $this->assertEquals($minifyArray, $domain->getMinify());
    }
}
