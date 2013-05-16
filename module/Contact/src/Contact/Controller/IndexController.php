<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

use Contact\Form\Contact;
use Contact\Model\Entity\Message;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $form = new Contact();
        $message = new Message();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form->setHydrator(new DoctrineEntity($em, 'Contact\Model\Entity\Message'));
        $form->bind($message);
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                var_dump($form->getData());
                $message->populate($form->getData());
                $service = $this->getServiceLocator()->get('Contact\Service\Contact');
                $result = $service->send($message);
                if ($result->isSuccess()) {
                    $this->flashMessenger()->addErrorMessage('Message successfuly sent');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                }
            }
        }
        return new ViewModel(array('form' => $form));
    }

}
