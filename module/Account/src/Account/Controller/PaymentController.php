<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PaymentController extends AbstractActionController
{

    public function indexAction()
    {
        if(!$this->IsGranted('account.payment.view')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $model   = new ViewModel();
        $service = $this->getServiceLocator()->get('Account\Service\Account');
        $result  = $service->getPaymentsPaginator();
        if ($result->isSuccess()) {
            $paginator = $result->getEntity();
            $paginator->setCurrentPageNumber($this->params()->fromRoute('ppage', 0));
            $paginator->setItemCountPerPage(10);
            $model->setVariable('payments', $paginator);
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        
        $result  = $service->getRepaymentsPaginator();
        if ($result->isSuccess()) {
            $paginator = $result->getEntity();
            $paginator->setCurrentPageNumber($this->params()->fromRoute('ppage', 0));
            $paginator->setItemCountPerPage(10);
            $model->setVariable('repayments', $paginator);
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        
        $result  = $service->getCommisionsPaginator();
        if ($result->isSuccess()) {
            $paginator = $result->getEntity();
            $paginator->setCurrentPageNumber($this->params()->fromRoute('ppage', 0));
            $paginator->setItemCountPerPage(10);
            $model->setVariable('commisions', $paginator);
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        
        $result  = $service->getPayoutsPaginator();
        if ($result->isSuccess()) {
            $paginator = $result->getEntity();
            $paginator->setCurrentPageNumber($this->params()->fromRoute('ppage', 0));
            $paginator->setItemCountPerPage(10);
            $model->setVariable('payouts', $paginator);
        } else {
            foreach ($result->getMessages() as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        
        return $model;
    }

}
