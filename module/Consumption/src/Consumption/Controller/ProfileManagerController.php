<?php

namespace Consumption\Controller;

use Acquisition\ImporterInterface;
use Consumption\EventService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * ProfilManagerController
 */
class ProfileManagerController extends AbstractActionController
{
    /**
     *
     * @var ImporterInterface
     */
    protected $journalManager;

    /**
     *
     * @var EventService
     */
    protected $eventService;

    /**
     *
     * @param \Acquisition\ImporterInterface $journalManager
     * @param type                           $eventService
     */
    public function __construct(ImporterInterface $journalManager, EventService $eventService)
    {
        $this->journalManager = $journalManager;
        $this->eventService = $eventService;
    }

    /**
     * consommateur en fonction du type (inscription,desincription,blacklistage)
     * @return boolean
     */
    public function consumeAction()
    {
        $type = $this->getRequest()->getQuery('type');
        $type = (int) $type;

        $mongo = new \MongoClient();
        $dbConsent = $mongo->consent;
        $cursorCollection = $dbConsent->cursor;
        $timestamp = $cursorCollection->findOne()["cursor"];
        $journalEvents = $this->journalManager->findAfterCursor($timestamp, $type);

        $consumer = $this->eventService->getConsumer($type);
        $nextCursor = $consumer->consume($journalEvents, $timestamp);

        $cursorCollection->update(
            array("type" => $type),
            array('$set' => array('cursor' => $nextCursor)),
            array("upsert" => true)
        );

        return true;
    }

    /**
     * return journalManager property
     * @return ImporterInterface
     */
    public function getJournalManager()
    {
        return $this->journalManager;
    }

    /**
     * return eventService
     * @return type
     */
    public function getEventService()
    {
        return $this->eventService;
    }

}
