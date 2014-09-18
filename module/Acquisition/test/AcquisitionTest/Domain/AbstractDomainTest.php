<?php

namespace AcquisitionTest\Domain;

use Acquisition\Domain\AbstractDomain;

class ImplAddress extends AbstractDomain
{
    protected $minify = array(
        'code postal' => 'cp',
        'ville'       => 'v',
    );
}

class ImplAbstractDomain extends AbstractDomain
{
    protected $minify = array(
        'firstname' => 'f',
        'lastname'  => 'l',
        'main_address' => 'ma', // InstanceOf ImplAddress
    );

    public function populate(array $array)
    {
        $array = $this->convertToArray($array, '$array');

        // Sub-Document of instance ImplAddress
        if (isset($array['main_address'])) {
            $array['main_address'] = new ImplAddress($array['main_address']);
        }

        parent::populate($array);
    }

    public function mongoPopulate(array $array)
    {
        $array = $this->convertToArray($array, '$array');

        // Sub-Document of instance ImplAddress
        if (isset($array['ma'])) {
            $array['main_address'] = new ImplAddress();
            $array['main_address']->mongoPopulate($array['ma']);
        }

        parent::mongoPopulate($array);
    }
}

class BadImplAbstractDomain extends AbstractDomain
{
    protected $minify = array(
        'agent'   => 'a',
        'address' => 'a',
    );
}

class BadMinifyImplAbstractDomain extends AbstractDomain
{
    protected $minify = array(
        'a' => 'b',
        'b' => 'c',
    );
}

class FalseSecurityImplAbstractDomain extends AbstractDomain
{
    const SECURE_WRITE_WITH_MINIFY = false;
}

class AbstractDomainTest extends \PHPUnit_Framework_TestCase
{

    protected $initArray;
    protected $minifyArray;

    public function setUp()
    {
        $this->initArray = array(
            'firstname' => 'Patrick',
            'lastname'  => 'Guiran',
            'main_address'   => array(
                'code postal' => '77600',
                'ville'       => 'Bussy saint Georges',
            ),
        );

        $this->minifyArray = array(
            'f' => 'Patrick',
            'l'  => 'Guiran',
            'ma'   => array(
                'cp' => '77600',
                'v'       => 'Bussy saint Georges',
            ),
        );
    }

    public function testAbstractDomain()
    {

        $domain = new ImplAbstractDomain();
        $domain->populate($this->initArray);

        $this->assertArrayNotHasKey('dont care', $domain);
        $this->assertArrayNotHasKey('dont care', $domain['main_address']);

        $this->assertEquals($this->initArray['firstname'], $domain['firstname']);
        $this->assertEquals($this->initArray['lastname'], $domain['lastname']);
        $this->assertTrue(is_scalar($domain['firstname']));
        $this->assertTrue(is_scalar($domain['lastname']));

        $this->assertInstanceOf('Acquisition\Domain\AbstractDomain', $domain['main_address']);
        $this->assertInstanceOf('AcquisitionTest\Domain\ImplAddress', $domain['main_address']);

        $this->assertEquals($this->initArray['main_address']['code postal'], $domain['main_address']['code postal']);
        $this->assertEquals($this->initArray['main_address']['ville'], $domain['main_address']['ville']);

    }

    public function testConstructorWithObject()
    {
        $domain = new ImplAbstractDomain();
        $domain->populate($this->initArray);

        $domain2 = new ImplAbstractDomain($domain);
        $this->assertEquals($domain, $domain2);
    }

    public function testToArray()
    {
        unset($this->initArray['main_address']);

        $resultArray = array(
            'firstname'    => 'Patrick',
            'lastname'     => 'Guiran',
            'main_address' => null,
        );

        $domain = new ImplAbstractDomain($this->initArray);
        $this->assertEquals($resultArray, $domain->toArray());
    }

    public function testIgnoreNotDefinedValues()
    {
        $this->initArray['dont care'] = "don't care of me";
        $this->initArray['main_address']['dont care'] = "don't care of me";

        $domain = new ImplAbstractDomain($this->initArray);

        $domainArray = $domain->toArray();
        $this->assertNotContains('dont care', $domainArray);
        $this->assertNotContains('dont care', $domainArray['main_address']);
    }

    public function testGetMinifyAndToMongoArray()
    {
        $domain = new ImplAbstractDomain();
        $domain->populate($this->initArray);

        $this->assertEquals($this->minifyArray, $domain->getMinify());
        $this->assertEquals($this->minifyArray, $domain->toMongoArray());

        $domain2 = new ImplAbstractDomain();
        $this->assertEquals(array(), $domain2->getMinify());
    }

    public function testMongoPopulate()
    {
        $this->minifyArray['z'] = 'pouet';

        $domain = new ImplAbstractDomain();
        $domain->mongoPopulate($this->minifyArray);

        $this->assertEquals($this->initArray, $domain->toArray());

        $domain2 = new ImplAbstractDomain();
        $domain2->mongoPopulate($this->minifyArray);
        $this->assertEquals($this->initArray, $domain2->getMaxify());
    }

    public function testWriteSecureWithMinify()
    {
        $domain = new ImplAbstractDomain();
        $domain->mongoPopulate($this->minifyArray);

        $domain['foo'] = 'bar';
        $domain['firstname'] = $this->initArray['firstname'] = 'Mickael';

        $this->assertEquals($this->initArray, $domain->toArray());
    }

    public function testWriteSecureWithMinifyException()
    {
        $domain = new FalseSecurityImplAbstractDomain();

        $this->setExpectedException('\InvalidArgumentException');
        $domain->populate($this->initArray);
    }

    /************************************************************************
     * Helpers
     ************************************************************************/
}
