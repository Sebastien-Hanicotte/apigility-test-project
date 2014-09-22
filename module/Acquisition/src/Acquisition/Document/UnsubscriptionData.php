<?php


namespace Acquisition\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class UnsubscriptionData extends AbstractDocument
{
    /** @ODM\Field(name="e",type="string")   */
    private $email;

    /** @ODM\Field(name="c",type="string") */
    private $civility;

    /** @ODM\Collection(name="d") */
    private $destination;
}