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
        $m = new \MongoClient();
        $collection = new \MongoCollection($m->test, "journal");

        $repositoryManager = new \Acquisition\DomainRepository\RepositoryManager($collection);
        return new JournalResource($repositoryManager);
    }
} 