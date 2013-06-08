<?php

namespace Invite\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $model   = new ViewModel();
        $service = $this->getServiceLocator()->get('Invite\Service\Invite');
        $result  = $service->getPaginator();
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

    public function editAction()
    {
        $form    = $this->getServiceLocator()->get('Contact\Form\Contact');
        $message = new Message();
        $service = $this->getServiceLocator()->get('Invite\Service\Invite');
        $result  = $service->get($this->params()->fromPost('id', 0));
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
}
