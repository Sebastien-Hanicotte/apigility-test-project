<?php

namespace Acquisition\Validator;

use Zend\Form\Form;
use Zend\Validator\ValidatorInterface;

class FormValidator implements ValidatorInterface
{
    protected $form;

    /**
     *
     * @param \Zend\Form\Form $form
     * @param type            $data
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     *
     * @param  type       $data
     * @return boolean
     * @throws \Exception | boolean
     */
    public function isValid($data)
    {
        $this->form->setData($data);
        if ($this->form->isValid() === false) {
            throw new \Exception(sprintf("%s", $this->getMessages()));
        }

        return true;
    }

    /**
     *
     * @return string
     */
    public function getMessages()
    {
        $messages = $this->form->getInputFilter()->getMessages();
        $result = $this->buildMessages($messages);

        return $result;
    }

    /**
     *
     * @param  array  $messages
     * @return string
     */
    public function buildMessages(array $messages)
    {
        $result = '';
        foreach ($messages as $key => $message) {
            if (is_array($message)) {
                $result .= $key .' => '. $this->buildMessages($message, $result);
            } else {
                $result .= $key .' => '. $message . PHP_EOL. '<br/>';
            }
        }

        return $result;
    }

    /**
     * convertit les entrées en objets mongos de type approprié
     * @param  array $data
     * @param  array $mapping
     * @return array $data
     */
    public function formateDataToMongo($data, $mapping)
    {
        foreach ($mapping as $key => $value) {
            switch ($value) {
                case 'int':
                    $data[$key] = new \MongoInt32($data[$key]);
                    break;
                case 'date':
                    $data[$key] = new \MongoDate(strtotime($data[$key]));
                    break;
                default:
                    break;
            }
        }

        return $data;
    }

}
