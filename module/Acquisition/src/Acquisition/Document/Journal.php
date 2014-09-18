<?php


namespace Acquisition\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(db="test", collection="journal") */
class Journal
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

    public function get($property)
    {
        $method = 'get'.ucfirst($property);
        if (method_exists($this, $method))
        {
            return $this->$method();
        } else {
            $propertyName = $property;
            return $this->$propertyName;
        }
    }

    public function set($property, $value)
    {
        $method = 'set'.ucfirst($property);
        if (method_exists($this, $method))
        {
            $this->$method($value);
        } else {
            $propertyName = $property;
            $this->$propertyName = $value;
        }
    }

    public function toArray()
    {
        $returnValue = array(
            'id' => $this->get('id'),
            'date' => $this->get('date'),
            'ip' => $this->get('ip'),
            'source' => $this->get('source'),
            'type' => $this->get('type'),

        );
        return $returnValue;
    }
}