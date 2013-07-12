<?php

namespace UserPage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{

    public function wellcomeAction()
    {
        if(!$this->IsGranted('userpage.wellcome')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $id      = $this->params()->fromRoute();
        $model   = new ViewModel();
        $service = $this->getServiceLocator()->get('UserPage\Service\Page');
        $result  = $service->get($id);
        if ($result->isSuccess()) {
            $model->setVariable('page', $result->getEntity());
        }
        return $model;
    }

    public function indexAction()
    {
        if(!$this->IsGranted('userpage.view')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $model   = new ViewModel();
        $service = $this->getServiceLocator()->get('UserPage\Service\Page');
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
        if(!$this->IsGranted('userpage.update')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $model   = new JsonModel();
        $service = $this->getServiceLocator()->get('UserPage\Service\Page');
        if ($this->request->isPost()) {
            $content = $this->params()->fromRoute('content', '');
            $title   = $this->params()->fromRoute('title', '');
            $result = $service->update($title, $content, $this->getUser());
            if ($result->isSuccess()) {
                $model->setVariable('message', array ('Page successfully updated'));
                $model->setVariable('success', true);
            } else {
                $model->setVariable('message', $result->getMessages());
            }
        }
        return $model;
    }

}
