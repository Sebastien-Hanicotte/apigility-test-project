<?php

namespace AcquisitionTest\Domain;

use Acquisition\Domain\AbstractDomain;
use Acquisition\Domain\DomainArray;

class ImplSimpleAddress extends AbstractDomain
{
    protected $minify = array(
        'code postal' => 'cp',
        'ville'       => 'v',
    );
}

class DomainArrayTest extends \PHPUnit_Framework_TestCase
{

    protected $initArray;
    protected $minifyArray;

    public function setUp()
    {
        $this->initArray = array(
            array(
                'code postal' => '77600',
                'ville'       => 'Bussy saint Georges',
            ),
            array(
                'code postal' => '94000',
                'ville'       => 'Créteil',
            ),
        );

        $this->minifyArray = array(
            array(
                'cp' => '77600',
                'v'  => 'Bussy saint Georges',
            ),
            array(
                'cp' => '94000',
                'v'  => 'Créteil',
            ),
        );
    }

    public function testDomainArray()
    {
        $addresses = array();
        foreach ($this->initArray as $value) {
            $addresses []= new ImplSimpleAddress($value);
        }
        $domain = new DomainArray($addresses);

        $this->assertInstanceOf('Acquisition\Domain\AbstractDomain', $domain);
        $this->assertInstanceOf('Acquisition\Domain\AbstractDomain', $domain[0]);

        $this->assertEquals($this->initArray, $domain->toArray());
        $this->assertEquals($this->initArray, $domain->getMaxify());
        $this->assertEquals($this->minifyArray, $domain->getMinify());
    }

    public function testWithSimpleList()
    {
        $list = array(
            "patrick",
            "mickael",
            "bastien"
        );
        $domain = new DomainArray($list);

        $this->assertInternalType('string', $domain[0]);

        $this->assertEquals($list, $domain->toArray());
        $this->assertEquals($list, $domain->getMaxify());
        $this->assertEquals($list, $domain->getMinify());
    }

    public function testExceptionForBadArrayValues()
    {
        $list = array(
            "patrick",
            array(
                "mickael",
                "bastien",
            )
        );

        $this->setExpectedException('\InvalidArgumentException');
        $domain = new DomainArray($list);
    }
}
