<?php

namespace AcquisitionTest\DomainRepository;

use Acquisition\DomainRepository\RepositoryManager;

class RepositoryManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;

    protected $initArray = array(
        'date'        => '01/01/1970 00:00:00',
        'ip'          => '127.0.0.1',
        'source'      => 'effidata',
        'status'      => true,
        'type'        => 1,
        'destination' => array(
            'Voici',
            'Gala'
        ),
        'profil'      => array(
            'firstname'     => 'Patrick',
            'lastname'      => 'Guiran',
            'birth_date'    => '22/03/1983',
            'email'         => 'pguiran@prismamedia.com',
            'civility'      => 'M',
            'job'           => 'dev',
            'phone'         => '01.75.50.45.45',
            'addresses'     => array(
                array(
                    'address'          => '3 cour du pavillon de chasse',
                    'postal_code'      => '77600',
                    'department'       => 'Seine-et-Marne',
                    'city'             => 'Bussy Saint Georges',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
                array(
                    'address'          => '92 avenue laferrière',
                    'postal_code'      => '94000',
                    'department'       => 'Val de Marne',
                    'city'             => 'Créteil',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
            ),
        ),
    );

    protected $result = array(array(
        'date'        => '01/01/1970 00:00:00',
        'ip'          => '127.0.0.1',
        'source'      => 'effidata',
        'status'      => true,
        'type'        => 1,
        'destination' => array(
            'Voici',
            'Gala'
        ),
        'profil'      => array(
            'firstname'     => 'Patrick',
            'lastname'      => 'Guiran',
            'birth_date'    => '22/03/1983',
            'email'         => 'pguiran@prismamedia.com',
            'civility'      => 'M',
            'job'           => 'dev',
            'phone'         => '01.75.50.45.45',
            'addresses'     => array(
                array(
                    'address'          => '3 cour du pavillon de chasse',
                    'postal_code'      => '77600',
                    'department'       => 'Seine-et-Marne',
                    'city'             => 'Bussy Saint Georges',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
                array(
                    'address'          => '92 avenue laferrière',
                    'postal_code'      => '94000',
                    'department'       => 'Val de Marne',
                    'city'             => 'Créteil',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
            ),
        ),
        'id' =>'',
    ));

    protected $json =
    '{
        "date": "2014-08-13",
        "ip": "127.0.0.1",
        "source": "Voici",
        "status": "actif",
        "profil": {
            "firstname": "Paul",
            "lastname": "Martin",
            "birth_date": "1993-07-11",
            "email": "test@prismamedia.com",
            "civility": "M",
            "job": "developpeur",
            "phone": "0123456789",
            "addresses": [
                {
                    "address": "35 Rue des Roses",
                    "postal_code": "75006",
                    "department": "75",
                    "city": "Paris",
                    "country": "France",
                    "address_checked": "35 Rue des Roses"
                },
                {
                    "address": "75 Rue des Lilas",
                    "postal_code": "69001",
                    "department": "69",
                    "city": "Lyon",
                    "country": "France",
                    "address_checked": "75 Rue des Lilas"
                }
            ]
        },
        "type": "inscription",
        "destination": [
            "C1",
            "C3"
        ]
    }';

    protected $fooData = array(
        'date'        => '01/01/1970 00:00:00',
        'ip'          => '127.0.0.1',
        'source'      => 'effidata',
        'status'      => true,
        'type'        => 1,
        'news'        => 1,
        'part'        => 1,
        'profil'      => array(
            'firstname'     => 'Patrick',
            'lastname'      => 'Guiran',
            'birth_date'    => '22/03/1983',
            'email'         => 'pguiran@prismamedia.com',
            'civility'      => 'M',
            'job'           => 'dev',
            'phone'         => '01.75.50.45.45',
            'addresses'     => array(
                array(
                    'address'          => '3 cour du pavillon de chasse',
                    'postal_code'      => '77600',
                    'department'       => 'Seine-et-Marne',
                    'city'             => 'Bussy Saint Georges',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
            ),
        ),
    );

    public function testFetchAll()
    {
        $definitionMock = $this->getMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);
        $this->assertEquals($this->result, $this->manager->fetchAll());

    }

    public function testGetJournal()
    {
        $definitionMock = $this->getMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->assertEquals($this->json, $this->manager->getJournal('foo@bar.com'));
    }

    public function testGetJournalNoResultException()
    {
        $definitionMock = $this->getBadMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->setExpectedException('Exception');
        $this->manager->getJournal('foo@bar.com');
    }

    public function testGet()
    {
        $definitionMock = $this->getMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->assertEquals($this->json, $this->manager->get('foo@bar.com', 'p.e'));
    }

    public function testGetNoResultExcpetion()
    {
        $definitionMock = $this->getBadMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->setExpectedException('Exception');
        $this->manager->get('foo@bar.com', 'p.e');
    }

    public function testSave()
    {
        $eventMock = $this->getEventMock();

        $definitionMock = $this->getMongoCollectionMock();

        $this->manager = new RepositoryManager($definitionMock);
        $this->assertEquals($this->initArray, $this->manager->save($eventMock));
    }

    public function testUpdateJournal()
    {
        $eventMock = $this->getEventMock();
        $definitionMock = $this->getMongoCollectionMock();

        $this->manager = new RepositoryManager($definitionMock);
        $this->assertEquals($this->json, $this->manager->updateJournal('foo@bar.com', $eventMock));
    }

    public function testDeleteJournal()
    {
        $definitionMock = $this->getMongoCollectionMock();

        $this->manager = new RepositoryManager($definitionMock);
        $this->manager->deleteJournal('foo@bar.com');

    }

     public function testDeleteJournalNoJournalException()
    {
        $definitionMock = $this->getBadMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->setExpectedException('Exception');
        $this->manager->deleteJournal('foo@bar.com');
    }

    public function testDispatch()
    {
        $definitionMock = $this->getMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->assertTrue($this->manager->dispatch($this->fooData));
        $this->assertTrue($this->manager->dispatch($this->initArray));

    }

    public function testFindAfterCursor()
    {
        $definitionMock = $this->getMongoCollectionMock();
        $this->manager = new RepositoryManager($definitionMock);

        $this->assertEquals($this->result, $this->manager->findAfterCursor(null, 1));
        $this->assertEquals($this->result, $this->manager->findAfterCursor(1234, 1));
    }

    /************************************************************************
     * Helpers
    ************************************************************************/

    private function getMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();
        $collection->expects($this->any())
                   ->method('find')
                   ->will($this->returnValue($this->result));

        $collection->expects($this->any())
                   ->method('findOne')
                      ->will($this->returnValue($this->json));

        $collection->expects($this->any())
                   ->method('insert')
                   ->will($this->returnValue($this->initArray));

        return $collection;
    }

    private function getBadMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
            ->method('findOne');

       return $collection;
    }

    private function getEventMock()
    {
        $mock = $this->getMockBuilder('Acquisition\Domain\Entity\JournalEvent')
                    ->disableOriginalConstructor()
                    ->getMock();

        $mock->expects($this->any())
                    ->method('toArray')
                    ->will($this->returnValue($this->initArray));

        return $mock;
    }
}
