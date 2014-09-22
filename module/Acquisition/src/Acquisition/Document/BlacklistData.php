<?php


namespace Acquisition\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument(db="test") */
class BlacklistData extends AbstractDocument {

    /** @ODM\EmbeddedOne(targetDocument="ProfileData", name="da.p") */
    protected $profile;

    protected $t;
}