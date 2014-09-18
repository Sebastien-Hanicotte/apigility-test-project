<?php

namespace AcquisitionTest\Domain\ValueObject;

use AcquisitionTest\Domain\AbstractDomainClassTest;

class SubDataTest extends AbstractDomainClassTest
{
    const DOMAIN_CLASS = 'Acquisition\Domain\ValueObject\SubData';

    protected $initArray = array(
        'destination' => array('C1', 'C2'),
        'profil'      => array(
            'firstname'     => 'Patrick',
            'lastname'      => 'Guiran',
            'birth_date'    => '22/03/1983',
            'email'         => 'pguiran@prismamedia.com',
            'civility'      => 'M',
            'job'           => 'dev',
            'phone'         => '01.75.50.45.45',
            'phone_2'       => '01.23.45.67.89',
            'addresses'     => array(
                array(
                    'address'          => '3 cour du pavillon de chasse',
                    'postal_code'      => '77600',
                    'department'       => 'Seine-et-Marne',
                    'city'             => 'Bussy Saint Georges',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
                array(
                    'address'          => '92 avenue laferrière',
                    'postal_code'      => '94000',
                    'department'       => 'Val d eMarne',
                    'city'             => 'Créteil',
                    'country'          => 'France',
                    'address_checked'  => true,
                ),
            ),
        )
    );

    protected $minifyArray = array(
        'de' => array('C1', 'C2'),
        'p' => array(
            'f'  => 'Patrick',
            'l'  => 'Guiran',
            'bd' => '22/03/1983',
            'e'  => 'pguiran@prismamedia.com',
            'c'  => 'M',
            'j'  => 'dev',
            'p'  => '01.75.50.45.45',
            'p2'       => '01.23.45.67.89',
            'a'  => array(
                array(
                    'a'  => '3 cour du pavillon de chasse',
                    'pc' => '77600',
                    'd'  => 'Seine-et-Marne',
                    'c'  => 'Bussy Saint Georges',
                    'co' => 'France',
                    'ac' => true,
                ),
                array(
                    'a'  => '92 avenue laferrière',
                    'pc' => '94000',
                    'd'  => 'Val d eMarne',
                    'c'  => 'Créteil',
                    'co' => 'France',
                    'ac'  => true,
                ),
            ),
        )
    );
}
