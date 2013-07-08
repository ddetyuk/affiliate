<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payment\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of IndexController
 *
 * @author ddetyuk
 */
class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        
    }

    public function cancelAction()
    {
        
    }

    public function successAction()
    {
        
    }

    public function withdrawAction()
    {
        if ($this->request->isPost()) {
            $sm     = $this->getServiceLocator();
            //FIXME:
            $email  = $this->params()->fromPost('email');
            $amount = $this->params()->fromPost('amount');
            if ($amount > 0) {
                $service = $sm->get('Payment\Gateway\Payza');
                $result  = $service->sendMoney($amount, $email);
                if ($result) {
                    $paymentService = $sm->get('Payment\Service\Payment');
                    $paymentService->payout($this->getUser(), $amount);
                    return array ();
                }
            }
        }
    }

    public function listenerAction()
    {
        //check is it post
        if ($this->request->isPost()) {
            $sm = $this->getServiceLocator();

            $addr    = $this->getRequest()->getServer()->get('REMOTE_ADDR');
            $service = $sm->get('Payment\Gateway\Payza');

            if ($service->checkRemoteAddr($addr)) {
                $result = $service->getIPNV2Handler($this->params()->fromPost('token'));
                if ($result) {

                    $email  = '';
                    $amount = '';

                    $userService = $sm->get('Payment\Service\Payment');
                    $user        = $userService->getUserByEmail($email);

                    $paymentService = $sm->get('Payment\Service\Payment');
                    $paymentService->payment($user, $amount);
                    return array ();
                }
            }
        }
        $this->notFoundAction();
    }

}
