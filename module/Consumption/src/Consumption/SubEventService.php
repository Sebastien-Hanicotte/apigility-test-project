<?php

namespace Consumption;

class SubEventService implements EventServiceInterface
{

    /**
	 * MongoDB
	 */
    protected $database;

    /**
	 * Constructor
	 */
    public function __construct(\MongoDB $dbConsent)
    {
        $this->database = $dbConsent;
    }

    public function consume(array $events, $cursor)
    {
        $nextCursor = $cursor;
        foreach ($events as $event) {
            $this->add($event);
            $nextCursor = $event["_id"];
        }

        return $nextCursor;
    }

    /**
	 * add an event
	 *
	 * @param $event array
	 */
    public function add($event)
    {
        foreach ($event["da"]["de"] as $destination) {
            if (! $this->hasInCollection($event["da"]["p"]["e"], $destination)) {
                $eventTmp = $event;
                unset($eventTmp["da"]["de"]);
                $eventTmp["s"] = "new";
                $this->database->selectCollection($destination)->insert($eventTmp);
            }
        }

        return $event;
    }

    /**
	 * ckeck if the email is in the "blacklist" collection
	 *
	 * @param  $email string
	 * @return boolean
	 */
    public function hasInCollection($email, $collection)
    {
        $result = $this->database->selectCollection($collection)->findOne(array("da.p.e" => $email));

        return ($result !== null && $result["s"] != "off");
    }

}
