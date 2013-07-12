<?php

namespace Invite\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        if(!$this->IsGranted('invite.view')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
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
        if(!$this->IsGranted('invite.update')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $model   = new JsonModel();
        $service = $this->getServiceLocator()->get('Invite\Service\Invite');
        if ($this->request->isPost()) {
            $post   = $this->request->getPost();
            $result = $service->update($post['subject'], $post['content'], $this->getUser());
            if ($result->isSuccess()) {
                $model->setVariable('message', array ('Letter successfully updated'));
                $model->setVariable('success', true);
            } else {
                $model->setVariable('message', $result->getMessages());
            }
        }
        return $model;
    }

    public function sendAction()
    {
        if(!$this->IsGranted('invite.send')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $model   = new JsonModel();
        $service = $this->getServiceLocator()->get('Invite\Service\Invite');
        if ($this-> request->isPost()) {
            $emails = $this->params()->fromPost('emails');
            if ($emails) {
                $result = $service->send(explode(',', $emails), $this->getUser());
                if ($result->isSuccess()) {
                    $model->setVariable('message', array ('Letters successfully sent'));
                    $model->setVariable('success', true);
                } else {
                    $model->setVariable('message', $result->getMessages());
                }
            }
        }
        return $model;
    }

}
