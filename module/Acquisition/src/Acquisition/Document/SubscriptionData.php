<?php


namespace Acquisition\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class SubscriptionData extends AbstractDocument
{

    /** @ODM\EmbedOne(targetDocument="ProfileData", name="p") */
    protected $profile;

    /** @ODM\Collection(name="de") */
    protected $destination = array();

    private $dataType = array(
        'profile' => 'ProfileData'
    );

    public function toArray()
    {
        $returnValue = array(
            'profile' => is_object($this->get('profile'))?$this->get('profile')->toArray():$this->get('profile'),
            'destination' => is_object($this->get('destination'))?$this->get('destination')->toArray():$this->get('destination'),
        );
        return $returnValue;
    }

    public function fromArray($data) {
//        var_dump($data);die();
        if (isset($data['profil'])) {
            $object = new ProfileData();
            $this->set('profile', $object->fromArray($data['profil']));
        }
        if (isset($data['destination'])) {
            $this->set('destination', $data['destination']);
        }
//        foreach ($data as $key=>$value) {
//            if (array_key_exists($key, $this->dataType)) {
//                $object = new $this->dataType[$key];
//                $this->set($key, $object->fromArray($data[$key]));
//            } else {
//                $this->set($key, $value);
//            }
//        }
        return $this;
    }

}

/** @ODM\EmbeddedDocument */
class ProfileData extends AbstractDocument {
    /** @ODM\Field(name="f", type="string") */
    protected $firstname;

    /** @ODM\Field(name="l", type="string") */
    protected $lastname;

    /** @ODM\Field(name="bd", type="string") */
    protected $birth_date;

    /** @ODM\Field(name="e", type="string") */
    protected $email;

    /** @ODM\Field(name="c", type="string") */
    protected $civility;

    /** @ODM\Field(name="j", type="string") */
    protected $job;

    /** @ODM\Field(name="p", type="string") */
    protected $phone;

    /** @ODM\Field(name="p2", type="string") */
    protected $phone_2;

    /** @ODM\EmbedMany(name="a", targetDocument="AddressData") */
    protected $addresses = array();

    public function toArray()
    {
        $returnValue = array(
            'firstname' => $this->get('firstname'),
            'lastname'  => $this->get('lastname'),
            'birth_date' => $this->get('birthDate'),
            'email' => $this->get('email'),
            'civility' => $this->get('civility'),
            'job' => $this->get('job'),
            'phone' => $this->get('phone'),
            'phone2' => $this->get('phone2'),
            'addresses' => array()
        );
        $addresses = $this->get('addresses');
        foreach($addresses as $address) {
            $returnValue['addresses'][]=is_object($address)?$address->toArray():$address;
        }
        return $returnValue;
    }

    public function fromArray($data) {
//        var_dump($data);die();
        foreach($data as $key=>$value) {
            $this->set($key, $value);
        }
        // Treating addresses
        $addresses = array();

        $this->set('addresses', array());
        foreach($data['addresses'] as $key=>$value) {
            $address = new AddressData();
            $addresses[] = $address->fromArray($value);
        }
        $this->set('addresses', $addresses);

        return $this;
    }
}

/** @ODM\EmbeddedDocument */
class AddressData extends AbstractDocument {
    /** @ODM\Field(name="a", type="string") */
    protected $address;

    /** @ODM\Field(name="pc", type="string") */
    protected $postal_code;

    /** @ODM\Field(name="d", type="string") */
    protected $department;

    /** @ODM\Field(name="c", type="string") */
    protected $city;

    /** @ODM\Field(name="co", type="string") */
    protected $country;

    /** @ODM\Field(name="ac", type="string") */
    protected $addressChecked;

    public function toArray()
    {
        $returnValue = array(
            'address' => $this->get('address'),
            'postal_code' => $this->get('postalCode'),
            'department' => $this->get('department'),
            'city' => $this->get('city'),
            'country' => $this->get('country'),
            'address_checked' => $this->get('address_checked'),
        );
        return $returnValue;
    }

    public function fromArray($data)
    {
        foreach($data as $key=>$value) {
            $this->set($key, $value);
        }
        return $this;
    }
}