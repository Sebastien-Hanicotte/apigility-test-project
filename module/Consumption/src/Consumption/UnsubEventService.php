<?php

namespace Consumption;

class UnsubEventService implements EventServiceInterface
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

    /**
     * consume the $events from the journal starting at a precise $cursor
     *
     * @param  array  $events
     * @param  string $cursor
     * @return string $nextCursor
     */
    public function consume(array $events, $cursor)
    {
        $nextCursor = $cursor;
        foreach ($events as $event) {
            $this->unsubscribe($event);
            $nextCursor = $event['_id'];
        }

        return $nextCursor;
    }

    /**
     * update a subscription to desactivate an email
     *
     * @param array $event
     */
    public function unsubscribe($event)
    {
        if (in_array('all', $event['da']['de'])) {
            $this->unsubscribeFromAll($event);
        } else {
            foreach ($event['da']['de'] as $destination) {
                if ($this->isSubscribed($event["da"]["e"], $destination)) {
                    $this->database->selectCollection($destination)->update(
                        array('da.p.e' => $event["da"]["e"]),
                        array('$set'=> array('s' => 'off'))
                    );
                }
            }
        }

        return $event;
    }

    /**
     * unsubscribe from every collection
     *
     * @param array $event
     */
    public function unsubscribeFromAll($event)
    {
        $allCollections = $this->database->getCollectionNames();
        foreach ($allCollections as $destination) {
            if ($this->isSubscribed($event["da"]["e"], $destination)) {
                $this->database->selectCollection($destination)->update(
                    array('da.p.e' => $event["da"]["e"]),
                    array('$set'=> array('s' => 'off'))
                );
            }
        }

        return $event;
    }

    /**
	 * ckeck if the email is in the collection and subscribed
	 *
	 * @param  $email string
	 * @return boolean
	 */
    public function isSubscribed($email, $collection)
    {
        $result = $this->database->selectCollection($collection)->findOne(array("da.p.e" => $email));

        return ($result !== null && $result['s'] != 'off');
    }

}
