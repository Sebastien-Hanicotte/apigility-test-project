// <?php

// namespace AcquisitionTest\Validator;

// use Acquisition\Validator\EmailAlreadyInDb;

// class EmailAlreadyInDbTest extends \PHPUnit_Framework_TestCase
// {

//     public function testIsValid()
//     {
//     	$data = array(
//     		'p' => array(
//     			'e' => 'foobartee'
//     		)
//     	);

//         $validator = new EmailAlreadyInDb();

//         $this->assertTrue($validator->isValid('foobartee'));

//         $m = new \MongoClient();
//         $dbTest = $m->test;
//         $journalCollection = $dbTest->journal;
//         $result = $journalCollection->insert($data);

//         $this->assertFalse($validator->isValid('foobartee'));

//         $journalCollection->remove($data);
//     }
// }
