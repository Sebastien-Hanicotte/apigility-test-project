<?php

namespace Acquisition;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Validator\ValidatorChain;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\ValidatorPluginManager;

use Application\Traits\ArrayTrait\ArrayGuardTrait;
use Application\Traits\ArrayTrait\ArrayHasKeyGuardTrait;

/**
 * ServiceForm
 */
class ServiceForm implements ServiceLocatorInterface
{
    use ArrayGuardTrait;
    use ArrayHasKeyGuardTrait;

    protected $definition;
    protected $validatorManager;
    protected $rules;
    protected $forms = array();

    /**
     * Constructor
     * @param \MongoCollection                       $definition
     * @param \Zend\Validator\ValidatorPluginManager $validatorManager
     */
    public function __construct(\MongoCollection $definition, ValidatorPluginManager $validatorManager)
    {
        $this->definition = $definition;
        $this->validatorManager = $validatorManager;
    }

    /**
     *
     * @param  string $name
     * @param  string $field
     * @return array
     */
    public function get($name, $field = 'forms')
    {
        $this->ensureHas($name);

        $fieldFilters = array(
            "_id" => false,
            "type" => false,
        );
        $fieldFilters[$field] = ($field == 'forms') ? false : 1;
        $results = $this->definition->find(
            array('forms' => array('$in' => array("$name"))),
            $fieldFilters
        );

        $documents = array();
        foreach ($results as $resultDoc) {
            $documents []= ($field === 'forms') ? $resultDoc : $resultDoc[$field];
        }

        return $documents;
    }

    /**
     * check if name is in collection definition
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        $results = $this->definition->findOne(array('forms' => array('$in' => array("$name"))));

        return ($results !== null);
    }

    /**
     * check if name exist
     * @param  string     $name
     * @throws \Exception
     */
    protected function ensureHas($name)
    {
        if ($this->has($name) === false) {
            throw new \Exception("Could not find $name in the DataBase");
        }
    }

    /**
     * return input filter
     * @return \Zend\InputFilter\InputFilter
     */
    private function getInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $validatorChain = new ValidatorChain();
        $validatorChain->setPluginManager($this->validatorManager);
        $factory->setDefaultValidatorChain($validatorChain);

        foreach ($this->rules as $rule) {
            $inputFilter->add($factory->createInput($rule));
        }

        return $inputFilter;
    }

    /**
     * initialyze input filter
     * @param  \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('Not used');
    }

    /**
     * set the inputFilter for the subforms
     * @param  string          $domainName
     * @param  array           $data
     * @return \Zend\Form\Form
     */
    public function getForm($domainName, $data)
    {
        if (in_array($domainName, array_keys($this->forms))) {
            return $this->forms[$domainName];
        }
        $inputFilter = $this->addSubFormInputFilter($domainName, $data);

        $form = new Form();
        $form->setInputFilter($inputFilter);
        $this->forms[$domainName] = $form;

        return $form;
    }

    /**
     * get the input filters adapted with the data given to set in a form
     *
     * @param $domainName  string
     * @param $data        array[string|int]
     */
    public function addSubFormInputFilter($domainName, $data)
    {
        $this->rules = $this->get($domainName);
        $inputFilter = $this->getInputFilter();
        $subformNames = $this->getSubFormsName($data);

        foreach ($subformNames as $subformName => $subFormData) {
            if ($this->isArrayWithIntKey($data[ $subformName ])) {
                $input = new InputFilter();
                foreach ($data[ $subformName ] as $key => $value) {
                    $domainArrayInput = $this->addSubFormInputFilter($subformName, $value);
                    $input->add($domainArrayInput, $key);
                }
            } elseif (is_array($data[ $subformName ])) {
                $formName = (is_array($subFormData)) ? $data['type'] : $subformName;
                $input = $this->addSubFormInputFilter($formName, $data[ $subformName ]);
            }

            $inputFilter->remove($subformName);
            $inputFilter->add($input, $subformName);
        }

        return $inputFilter;
    }

    /**
     * get a SubForm
     * @param  array $data
     * @return array
     */
    protected function getSubFormsName(array $data)
    {
        $subformNames = array();
        foreach ($this->rules as $rule) {
            try {
                $this->guardArrayHasKey($rule, 'subform');
                $this->guardArrayHasKey($data, $rule['name']);
                $subformNames[$rule['name']] = $rule['subform'];
            } catch (\Exception $e) {
                // Nothing to do
            }
        }

        return $subformNames;
    }

    /**
     * Check if array as integer key
     * @param  array   $array
     * @return boolean
     */
    protected function isArrayWithIntKey($array)
    {
        $keys = array_keys($array);
        $result = array_map('is_int',$keys);
        if (in_array(false, $result)) {
            return false;
        }

        return true;

    }

    /**
     * return type
     * @param  string $name
     * @return array
     */
    public function getTypes($name)
    {
        $documents = array();
        $results = $this->definition->find(
            array('forms' => array('$in' => array("$name"))), array('_id' => false, 'type' => 1, 'name' => 1)
        );
        foreach ($results as $resultDoc) {
            $documents[$resultDoc['name']] = $resultDoc['type'];
        }

        return $documents;

    }

    /**
     * method magic
     * @param string $method
     * @param array  $arguments
     *
     */
    public function __call($method, $arguments)
    {
        if (substr($method, 0, 3) === 'get' &&
            substr($method, -1, 1) === 's' &&
            count($arguments) === 1
        ) {
            $what = strtolower(substr($method, 3, strlen($method) - 4));

            return $this->get($arguments[0], $what);
        }
    }
}
