<?php

namespace Acquisition\DomainRepository;

use Acquisition\Domain\Entity\AbstractEntity;
use Acquisition\ImporterInterface;
use Acquisition\Domain\AbstractDomain;

class RepositoryManager implements ImporterInterface
{
    /**
     * @var object
     */
    protected $collection;

    /**
     * Constructor
     */
    public function __construct(\MongoCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get the whole journal
     *
     * @return MongoCursor $result
     */

    public function fetchAll()
    {
        $result = array();
        $cursorPersonne = $this->collection->find();
        foreach ($cursorPersonne as $personne) {
            array_push($result, $personne);
        }

        return $result;
    }

    /**
     * Get the journal of somebody
     *
     * @param  string                    $id
     * @return object                    $result
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getJournal($id)
    {
        $result = $this->collection->findOne(array('p.e' => $id));
        if ($result === NULL) {
            throw new \RuntimeException("Could not find the row");
        }

        return $result;

    }

    /**
     *
     * @param  string|integer    $id
     * @param  string            $headers
     * @return MongoCursor
     * @throws \RuntimeException
     */
    public function get($id,$headers='p.e')
    {
        $result = $this->collection->findOne(array($headers => $id));
        if ($result === NULL) {
            throw new \RuntimeException("Could not find the row");
        }

        return $result;
    }

    /**
     * Insert and return the $object
     *
     * @param  object $object
     * @return array  $object
     */
    public function save(AbstractDomain $object)
    {
        $data = $object->toMongoArray();
        if (is_null($data['_id'])) {
            unset($data['_id']);
        }
        $this->collection->insert($data);

        return $object->toArray();
    }

    /**
     * Update and return the journal
     *
     * @param  object $journal
     * @param  string $id
     * @return object $result
     */
    public function updateJournal($id, $object)
    {
        $data = $object;
        if ($object instanceof AbstractEntity) {
            $data = $object->toMongoArray();
        }
        $this->collection->update(array('p.e' => $id), $data);
        $result = $this->collection->findOne($data);

        return $result;

    }

    /**
     * Delete from the journal
     *
     * @param string $id
     */
    public function deleteJournal($id)
    {
        $exist = $this->collection->findOne(array('p.e' => $id));
            if (isset($exist)) {
                $this->collection->remove(array('p.e' => $id));
            } else {throw new \Exception("Could not find the row");}
    }

    /**
     * return cursor with all row without key check
     * @return MongoCursor
     */

    public function findAfterCursor($timestamp, $type)
    {
        $result = array();

        if (is_null($timestamp)) {
            $cursorAfterCursor = $this->collection->find(
                array(
                    "t" => $type
                )
            );
        } else {
            $cursorAfterCursor = $this->collection->find(
                array(
                    "_id" => array('$gt' => $timestamp),
                    "t" => $type
                )
            );
        }

        foreach ($cursorAfterCursor as $afterCursor) {
            $result[] = $afterCursor;
        }

        return $result;
    }

        /**
     * return key minify in an array
     * @param  string $class
     * @return array
     */
    public function getMinify($class)
    {
        /*
          if ($class == 'domainarray') {
          return array();
          } */

        $result = $this->findOne($class);

        if (array_key_exists('minify', $result)) {
            return $result['minify'];
        }

        return $result;
    }

    /**
     * return a row in an array
     * @param  string $class
     * @return array
     */
    public function findOne($class)
    {
        $result = array();
        $res = $this->collection->findOne(array("class" => $class,));

        if (count($res) < 1) {
            return $result;
        }

        foreach ($res as $key => $row) {
            $result[$key] = $row;
            //array_push($result, $row);
        }

        return $result;
        //return $mongoCursor;
    }

    /**
     * dispatch les enregistrements du journal dans différentes collections
     * @param array $data
     */
    public function dispatch($data)
    {
        $result = false;
        if (!isset($data['destination'])) {
            if (isset($data['news']) && isset($data['part'])) {
                if ($data['news']==1) {
                    $this->collection->insert('newsletters',$data);
                    $result = true;
                }

                if ($data['part']==1) {
                    $this->collection->insert('partenaires',$data);
                    $result = true;
                }
            }
        } else {
            /**
             * @ new version with destination key
             */
            $result = true;
        }

        return $result;
    }

}
