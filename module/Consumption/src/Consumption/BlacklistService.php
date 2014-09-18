<?php

namespace Consumption;

class BlacklistService implements EventServiceInterface
{

    /**
	 * MongoDb
	 */
    protected $database;

    /**
     * @var \MongoCollection (blacklist)
     */
    protected $blacklistCollection;

    /**
	 * Constructor
	 */
    public function __construct(\MongoCollection $blacklistCollection, \MongoDB $dbConsent)
    {
        $this->database = $dbConsent;
        $this->blacklistCollection = $blacklistCollection;
    }

    /**
	 * add an event
	 *
	 * @param $event array
	 */
    public function add($event)
    {
        if (! $this->has($event["da"]["e"])) {
            $this->blacklistCollection->insert($event);
        }

        return $event;
    }

    /**
	 * return the document with the $email in the blacklist collection
	 *
	 * @param $email string
	 * @return array
	 */
    public function get($email)
    {
        $this->ensureHas($email);

        $fieldFilters = array("_id" => false);
        $results = $this->blacklistCollection->find(
            array('da.e' => $email),
            $fieldFilters
        );

        return $results;
    }

    /**
     * check if email is in blacklist
     *
     * @param  string     $email
     * @throws \Exception
     */
    public function ensureHas($email)
    {
        if ($this->has($email) === false) {
            throw new \Exception(sprintf("Could not find %s in blacklist", $email));
        }
    }

    /**
	 * check if the email is in the "blacklist" collection
	 *
	 * @param  $email string
	 * @return boolean
	 */
    public function has($email)
    {
        $result = $this->blacklistCollection->findOne(array("da.e" => $email));

        return ($result !== null);
    }

    /**
	 * return all documents in the "blacklist" collection
	 *
	 * @return array
	 */
    public function getList()
    {
        $result = array();
        $blacklistedEmails = $this->blacklistCollection->find();
        foreach ($blacklistedEmails as $blacklistedEmail) {
            array_push($result, $blacklistedEmail);
        }

        return $result;
    }

    /**
     * consume the data of type 3 from the journal
     * @param $cursor
     * @param array $events
     */
    public function consume(array $events, $cursor)
    {
        $nextCursor = $cursor;
        foreach ($events as $event) {
            $collectionNames = $this->database->getCollectionNames();
            foreach ($collectionNames as $collectionName) {
                    $this->database->selectCollection($collectionName)->remove(array('da.p.e' => $event["da"]["e"]));
            }
            $nextCursor = $event["_id"];
            $this->add($event);
        }

        return $nextCursor;

    }
}
