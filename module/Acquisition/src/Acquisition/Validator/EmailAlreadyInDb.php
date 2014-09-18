<?php

namespace Acquisition\Validator;

use Zend\Validator\AbstractValidator;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Traits\ArrayTrait\ArrayGuardTrait;

/**
 * @codeCoverageIgnore
 */
class EmailAlreadyInDb extends AbstractValidator
{

    use ArrayGuardTrait;

    const EMAIL_FOUND = "email_found";
    const MISSING_TOKEN = 'missingToken';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::EMAIL_FOUND => "the email is already register in the '%value%' consent",
        self::MISSING_TOKEN => 'No token was provided to match against'
    );

    /**
     * @var array
     */
    protected $messageVariables = array(
        'token' => 'tokenString'
    );

    /**
     * Original token against which to validate
     * @var string
     */
    protected $tokenString;
    protected $token;
    protected $strict  = true;
    protected $literal = false;

    /**
     * Sets validator options
     *
     * @param mixed $token
     */
    public function __construct($token = null)
    {
        if ($token instanceof Traversable) {
            $token = ArrayUtils::iteratorToArray($token);
        }

        if (is_array($token) && array_key_exists('token', $token)) {
            if (array_key_exists('strict', $token)) {
                $this->setStrict($token['strict']);
            }

            if (array_key_exists('literal', $token)) {
                $this->setLiteral($token['literal']);
            }

            $this->setToken($token['token']);
        } elseif (null !== $token) {
            $this->setToken($token);
        }

        parent::__construct(is_array($token) ? $token : null);
    }

    /**
     * Retrieve token
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token against which to compare
     *
     * @param  mixed     $token
     * @return Identical
     */
    public function setToken($token)
    {
        $this->tokenString = (is_array($token) ? var_export($token, true) : (string) $token);
        $this->token       = $token;

        return $this;
    }

    /**
     * Returns the strict parameter
     *
     * @return bool
     */
    public function getStrict()
    {
        return $this->strict;
    }

    /**
     * Sets the strict parameter
     *
     * @param  bool      $strict
     * @return Identical
     */
    public function setStrict($strict)
    {
        $this->strict = (bool) $strict;

        return $this;
    }

    /**
     * Returns the literal parameter
     *
     * @return bool
     */
    public function getLiteral()
    {
        return $this->literal;
    }

    /**
     * Sets the literal parameter
     *
     * @param  bool      $literal
     * @return Identical
     */
    public function setLiteral($literal)
    {
        $this->literal = (bool) $literal;

        return $this;
    }

    /**
     * Returns true if and only if a token has been set and the provided value
     * matches that token.
     *
     * @param  mixed                      $value
     * @param  array                      $context
     * @return bool
     * @throws Exception\RuntimeException if the token doesn't exist in the context array
     */
    public function isValid($value, array $context = null)
    {
        $token = $this->getToken();
        $this->guardForArray($value, "value");

        if ($token === null) {
            $this->error(self::MISSING_TOKEN);

            return false;
        }
        if (isset($context['profil'])) {
            $token = $context['profil'][$token];
        }
        $m = new \MongoClient();
        $dbTest = $m->consent;

        foreach ($value as $consentement) {
            $collection = $dbTest->$consentement;
            $result = $collection->findOne(array("da.p.e" => $token));
            if ($result) {
                $this->setValue($consentement);
                $this->error(self::EMAIL_FOUND);

                return false;
            }
        }

        return true;
    }
}
