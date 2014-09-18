<?php
/**
 * Created by PhpStorm.
 * User: Sebastien
 * Date: 17/09/2014
 * Time: 18:21
 */
namespace Acquisition\V1\Rest\Journal;


class JournalResourceFactory
{

    public function __invoke($services) {
        return new JournalResource($services->get('doctrine.documentmanager.odm_default'));
    }
} 