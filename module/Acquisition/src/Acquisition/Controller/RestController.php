<?php

namespace Acquisition\Controller;

use Acquisition\Domain\Entity\JournalEvent;
use Acquisition\ServiceForm;
use Acquisition\ImporterInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

use Acquisition\Validator\FormValidator;

class RestController extends AbstractRestfulController
{

    protected $journalManager;
    protected $serviceForm;
    protected $documentManager;

    public function __construct(ImporterInterface $journalManager, ServiceForm $serviceForm)
    {
        $this->journalManager = $journalManager;
        $this->serviceForm = $serviceForm;
    }

    /**
	 * renvoie la liste des documents
	 *
	 * @return JsonModel
	 */
    public function getList()
    {
//        $result = $this->journalManager->fetchAll();
//
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
//        $journal = new \Acquisition\Documents\Journal();
//        $journal->set('date', '2014-09-18 12:01:25');
//        $journal->set('ip', '127.0.0.1');
//        $journal->set('source', 'Geo');
//        $journal->set('type', 1);
//        $dm->persist($journal);
//        $dm->flush();

        $journal = $dm->getRepository('\Acquisition\Document\Journal')->findAll();

        $temp = array();

        foreach ($journal as $item) {
            $temp[] = $item->toArray();
        }

        return new JsonModel($temp);

    }

    /**
	 * renvoie les informations d'un document en fonction de l'id
	 * @param integer $id
	 * @return JsonModel
	 */
    public function get($id)
    {
        $result = $this->journalManager->getJournal($id);

        return new JsonModel($result);
    }

    public function create($data)
    {
        $result = array();
        if (isset($data['data']['emails']) && is_array($data['data']['emails'])) {
            foreach ($data['data']['emails'] as $email) {
                $dataTmp = $data;
                $dataTmp['data']['email'] = $email;
                unset($dataTmp['data']['emails']);
                $result[] = $this->createEvent($dataTmp);
            }
        } else {
            $result[] = $this->createEvent($data);
        }

        return new JsonModel($result);
    }

    /**
	 * crée un document dans la collection
	 * @param array $data
	 * @return JsonModel
	 */
    public function createEvent($data)
    {
        $response = $this->getResponse();

        $event = new JournalEvent($data);
        $data = $event->toArray();

        $formValidator = new FormValidator($this->serviceForm->getForm('journal', $data));

        $mapping = $this->serviceForm->getTypes('journal');
        $event = $formValidator->formateDataToMongo($event, $mapping);

        try {
            $formValidator->isValid($data);
            $response->setStatusCode(201);
        } catch (\Exception $e) {
            if (preg_match('/email_found/', $e->getMessage())) {
                $response->setStatusCode(409);
            } else {
                $response->setStatusCode(400);
            }

            return array('error' => $e->getMessage());
        }

        $this->journalManager->save($event);

        return $event->toArray();

    }

    /**
	 * met à jour un document
	 * @param interger 	$id
	 * @param array 	$data
	 * @return JsonModel
	 */
    public function update($id,$data)
    {
        $result = $this->journalManager->updateJournal($id, $data);
        $this->getResponse()->setStatusCode(202);

        return new JsonModel($result);
    }

    /**
	 * supprimer un document
	 * @param  integer $id
	 * @return JsonModel
	 */
    public function delete($id)
    {
        $this->journalManager->deleteJournal($id);
        $this->getResponse()->setStatusCode(202);

        return new JsonModel(array('data' => 'success','type'=>'DELETE'));
    }

    public function setDocumentManager(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        return $this;
    }
}
