<?php

return array(
    'router' => array(
        'routes' => array(
            'profilmanager2' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/profilmanager2/consume[?type=:type]',
                    'constraints' => array(
                        'type' => "^[1-9][0-9]*$",
                    ),
                    'defaults' => array(
                        'controller' => 'Consumption\Controller\ProfileManager',
                        'action' => 'consume'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Consumption\Controller\ProfileManager' => 'Consumption\Factory\Controller\ProfileManagerControllerFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'BlackListService' => 'Consumption\Factory\Service\BlackListServiceFactory',
            'SubEventService' => 'Consumption\Factory\Service\SubEventServiceFactory',
            'UnsubEventService' => 'Consumption\Factory\Service\UnsubEventServiceFactory'
        )
    )
);
