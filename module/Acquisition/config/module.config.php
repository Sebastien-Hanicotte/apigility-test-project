<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Acquisition\Controller\Rest',
                    ),
                ),
            ),
            'acquisition' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/api/acquisition[/:id]',
                    'constraints' => array(
                        'id' => "^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
                    ),
                    'defaults' => array(
                        'controller' => 'Acquisition\Controller\Rest',
                    ),
                ),
            ),
            'profilmanager' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/profilmanager/import',
                    'defaults' => array(
                        'controller' => 'Acquisition\Controller\ProfilManager',
                        'action' => 'import',

                    ),
                ),
            ),
            'acquisition.rest.journal' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/journal[/:journal_id]',
                    'defaults' => array(
                        'controller' => 'Acquisition\\V1\\Rest\\Journal\\Controller',
                    ),
                ),
            ),
        ),
    ),

    'validators' => array(
        'invokables' => array(
            'EmailAlreadyInDb' => 'Acquisition\Validator\EmailAlreadyInDb',
            'CheckDestination' => 'Acquisition\Validator\CheckDestination',
            'EmailBlacklisted' => 'Acquisition\Validator\EmailBlacklisted',
        )
    ),
    'controllers' => array(
        'factories' => array(
            "Acquisition\Controller\Rest" => 'Acquisition\Factory\Controller\RestControllerFactory',
            "Acquisition\Controller\ProfilManager" => 'Acquisition\Factory\Controller\ProfilManagerFactory'

        )
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            "ServiceForm"=> "Acquisition\Factory\Service\ServiceFormFactory",
            'Acquisition\\V1\\Rest\\Journal\\JournalResource' => 'Acquisition\\V1\\Rest\\Journal\\JournalResourceFactory',
        ),
        'abstract_factories' => array(
            "RepositoryManager" => "Acquisition\DomainRepository\RepositoryManagerAbstractFactory"
        )
    ),

    'Acquisition\Controller\RestController' => array(
        'parameters' => array(
            'documentManager' => 'mongo_dm',
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'acquisition.rest.journal',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Acquisition\\V1\\Rest\\Journal\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Acquisition\\V1\\Rest\\Journal\\Controller' => array(
                0 => 'application/vnd.acquisition.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Acquisition\\V1\\Rest\\Journal\\Controller' => array(
                0 => 'application/vnd.acquisition.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-rest' => array(
        'Acquisition\\V1\\Rest\\Journal\\Controller' => array(
            'listener' => 'Acquisition\\V1\\Rest\\Journal\\JournalResource',
            'route_name' => 'acquisition.rest.journal',
            'route_identifier_name' => 'journal_id',
            'collection_name' => 'journal',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Acquisition\\V1\\Rest\\Journal\\JournalEntity',
            'collection_class' => 'Acquisition\\V1\\Rest\\Journal\\JournalCollection',
            'service_name' => 'Journal',
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Acquisition\\V1\\Rest\\Journal\\JournalEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'acquisition.rest.journal',
                'route_identifier_name' => 'journal_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Acquisition\\V1\\Rest\\Journal\\JournalCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'acquisition.rest.journal',
                'route_identifier_name' => 'journal_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'Acquisition\\V1\\Rest\\Journal\\Controller' => array(
            'input_filter' => 'Acquisition\\V1\\Rest\\Journal\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Acquisition\\V1\\Rest\\Journal\\Validator' => array(
            0 => array(
                'name' => 'ip',
                'required' => true,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Ip',
                        'options' => array(
                            'allowipv4' => true,
                            'allowipv6' => false,
                        ),
                    ),
                ),
                'description' => 'Fill with a valid IPV4',
            ),
            1 => array(
                'name' => 'source',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '140',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
