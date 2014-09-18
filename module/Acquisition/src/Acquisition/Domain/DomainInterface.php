<?php

namespace Acquisition\Domain;

interface DomainInterface
{
    /**
     * Return an array with all fileds which specified a Domain.
     *
     * Unset field has a null value
     *
     * @return array
     */
    public function toArray();

    /**
     * Return an array with minify key and with only set fields
     *
     * Contrairy to toArray(), unset field are not present in returned array
     *
     * @return array
     */
    public function toMongoArray();
}
