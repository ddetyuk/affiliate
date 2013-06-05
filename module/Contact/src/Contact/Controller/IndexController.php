<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Contact\Model\Entity\Message;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $model = new ViewModel();
        $service = $this->getServiceLocator()->get('Contact\Service\Contact');
        $result = $service->getPaginator();
        if ($result->isSuccess()) {
            $paginator = $result->getEntity();
            $paginator->setCurrentPageNumber($this->params()->fromRoute('page', 0));
            $paginator->setItemCountPerPage(10);
            $model->setVariable('paginator', $paginator);
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return $model;
    }

    public function addAction()
    {
        $form = $this->getServiceLocator()->get('Contact\Form\Contact');
        $message = new Message();
        $form->bind($message);
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get('Contact\Service\Contact');
                $message->setUser($this->getUser());
                $result = $service->create($message);
                if ($result->isSuccess()) {
                    $this->flashMessenger()->addSuccessMessage('Message successfully sent');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                }
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function editAction()
    {
        $form = $this->getServiceLocator()->get('Contact\Form\Contact');
        $message = new Message();
        $service = $this->getServiceLocator()->get('Contact\Service\Contact');
        $result = $service->get($this->params()->fromPost('id', 0));
        if ($result->isSuccess()) {
            $message = $result->getEntity();
            $form->bind($message);
            if ($this->request->isPost()) {
                $form->setData($this->request->getPost());
                if ($form->isValid()) {
                    $result = $service->update($message);
                    if ($result->isSuccess()) {
                        $this->flashMessenger()->addSuccessMessage('Message successfully updated');
                    } else {
                        foreach ($result->getMessages() as $message) {
                            $this->flashMessenger()->addErrorMessage($message);
                        }
                    }
                }
            }
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function deleteAction()
    {
        $message = new Message();
        $contactService = $this->getServiceLocator()->get('Contact\Service\Contact');
        $result = $contactService->get($this->params()->fromPost('id', 0));
        if ($result->isSuccess()) {
            $message = $result->getEntity();
            $service = $this->getServiceLocator()->get('Contact\Service\Contact');
            $result = $service->delete($message);
            if ($result->isSuccess()) {
                $this->flashMessenger()->addSuccessMessage('Message successfully removed');
            } else {
                foreach ($result->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
            }
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return new ViewModel();
    }

}
