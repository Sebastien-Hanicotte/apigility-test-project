<?php
namespace Acquisition\V1\Rest\Journal;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Acquisition\ImporterInterface;

class JournalResource extends AbstractResourceListener
{
    protected $dm;

    public function __construct(ImporterInterface $journalDocumentManager) {
        $this->dm = $journalDocumentManager;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $journal = new \Acquisition\Document\Journal();
        $journal->fromArray($data);
        $this->dm->getDocumentManager()->persist($journal);
        $this->dm->getDocumentManager()->flush();
        if ($journal->get('id') != '') {
            return $journal->toArray();
        }
        return new ApiProblem(500, 'Unable to create the new journal entry');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $journal = $this->dm->find($id);
        $this->dm->getDocumentManager()->remove($journal);
        $this->dm->getDocumentManager()->flush();
        return new ApiProblem(202, 'The DELETE method has not been executed...');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {

        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($email)
    {
        return $this->dm->getJournal($email);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $result = $this->dm->fetchAll();
        return $result;
    }


    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
