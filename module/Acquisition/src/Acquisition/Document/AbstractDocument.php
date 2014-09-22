<?php
/**
 * Created by PhpStorm.
 * User: Sebastien
 * Date: 19/09/2014
 * Time: 14:30
 */

namespace Acquisition\Document;


abstract class AbstractDocument {

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
} 