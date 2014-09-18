<?php

namespace AcquisitionTest\Domain;

abstract class AbstractDomainClassTest extends \PHPUnit_Framework_TestCase
{
    const DOMAIN_CLASS = '';

    protected $domain;

    protected $initArray;
    protected $minifyArray;

    public function setUp()
    {
        $class = static::DOMAIN_CLASS;
        $this->domain = new $class($this->initArray);
    }

    public function testToArray()
    {
        $randomIndex = rand(1, count($this->initArray));
        $randomKey = null;
        foreach (array_keys($this->initArray) as $key) {
            if (--$randomIndex == 0) {
                $randomKey = $key;
                break;
            }
        }
        unset($this->initArray[ $randomKey ]);

        $class = static::DOMAIN_CLASS;
        $this->domain = new $class($this->initArray);

        $this->initArray[ $randomKey ] = null;
        $toArray = $this->domain->toArray();
        $this->assertNull($toArray[$randomKey]);
        $this->assertEquals($this->initArray, $toArray);
    }

    public function testMinify()
    {
        $this->assertEquals($this->minifyArray, $this->domain->getMinify());
    }

    public function testMaxify()
    {
        $this->assertEquals($this->initArray, $this->domain->getMaxify());
    }

    public function testMinifyAndMaxifyConsistance()
    {
        $minifyArray = $this->domain->getMinify();
        $maxifyArray = $this->domain->getMaxify();
        $mergeArray  = array_merge($minifyArray, $maxifyArray);

        $initCount   = count($this->initArray);
        $maxifyCount = count($maxifyArray);
        $minifyCount = count($minifyArray);
        $mergeCount  = count($mergeArray);

        $this->assertEquals($initCount, $maxifyCount);
        $this->assertEquals($maxifyCount, $minifyCount);
        $this->assertEquals($mergeCount, $minifyCount + $maxifyCount);
    }

}
