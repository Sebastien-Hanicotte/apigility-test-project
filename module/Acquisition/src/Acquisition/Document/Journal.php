<?php


namespace Acquisition\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Acquisition\Document\SubscriptionData;
use Acquisition\Document\UnsubscriptionData;

/** @ODM\Document(db="test", collection="journal") */
class Journal extends AbstractDocument
{
    /** @ODM\Id(name="_id") */
    private $id;

    /** @ODM\Field(name="d", type="date") */
    private $date;

    /** @ODM\Field(name="i", type="string") */
    private $ip;

    /** @ODM\Field(name="s", type="string") */
    private $source;

    /** @ODM\Field(name="t", type="int") */
    private $type;

    /** @ODM\EmbedOne(name="da", discriminatorMap={
     *    "subscription"="SubscriptionData",
     *    "unsubscription"="UnsubscriptionData"
     * })
     */
    /** @ODM\EmbedOne(name="da", targetDocument="SubscriptionData") */
    private $data;

    public function toArray()
    {
        $returnValue = array(
            'id' => $this->get('id'),
            'date' => $this->get('date'),
            'ip' => $this->get('ip'),
            'source' => $this->get('source'),
            'type' => $this->get('type'),
        );

        if (is_object($this->get('data'))) {
            $returnValue['data'] = $this->get('data')->toArray();
        }
        return $returnValue;
    }

    public function fromArray($data)
    {
        foreach($data as $key=>$value) {
            $this->set($key, $value);
        }
        $dataValue = null;
        if (array_key_exists('data', $data)) {
            switch($this->get('type')) {
                case '1':
                    $dataValue = new SubscriptionData();
                    $dataValue->fromArray($data->data);
            }
        }
        $this->set('data', $dataValue);
    }
}