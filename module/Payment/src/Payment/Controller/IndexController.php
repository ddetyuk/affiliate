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
        $sm = $this->getServiceLocator();
        $service = $sm->get('PayzaPayment\Service\PayzaService');
        echo  $service->getBalance();
//        echo  $service->sendMoney('5','client_1_ddetyuk@gmail.com');
    }

    public function cancelAction()
    {
        
    }

    public function successAction()
    {
        
    }

    public function listenerAction()
    {
        //check is it post
        if( $this->request->isPost() ){
            $sm = $this->getServiceLocator();
            //checking IPNServerIP
            $config = $sm->get('Config');
            if( $_SERVER['REMOTE_ADDR'] == $config['payza']['IPNServerIP'] ){
                $service = $sm->get('PayzaPayment\Service\PayzaService');
                $result = $service->getIPNV2Handler($this->fromPost('token'));
                if($result){
                    //send event
                    return array();
                }
            }
        }
        $this->notFoundAction();
    }

}
