<?php

namespace Acquisition\Validator;

use Zend\Validator\AbstractValidator;

/**
 * @codeCoverageIgnore
 */
class EmailBlacklisted extends AbstractValidator
{

    const EMAIL_FOUND = "email_blacklisted";

    protected $messageTemplates = array(
        self::EMAIL_FOUND => "'%value%' is blacklisted in database"
    );

    public function isValid($value)
    {
        $m = new \MongoClient();
        $dbTest = $m->test;
        $this->setValue($value);
        $result = $dbTest->blacklist->findOne(array("da.e" => $value));

        if ($result) {
            $this->error(self::EMAIL_FOUND);

            return FALSE;
        }

        return TRUE;
    }

}
