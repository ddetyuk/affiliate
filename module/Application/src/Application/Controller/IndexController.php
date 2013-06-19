<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function aboutAction()
    {
        return new ViewModel();
    }

    public function setTheRateAction()
    {
        $invited = 0;
        $joined = 0;
        return new ViewModel(array('invited' => $invited, 'joined'  => $joined));
    }

    public function purchaseTheRateAction()
    {
        return new ViewModel();
    }

    public function balanceAction()
    {
        
    }

    public function howItWorksAction()
    {
        return new ViewModel();
    }

    public function stepByStepAction()
    {
        return new ViewModel();
    }

    public function wellcomeAction()
    {
        return new ViewModel();
    }

    public function invitationAction()
    {
        if($this->getUser()->getLetter()->getInvited() > 5){
            return $this->notFoundAction();
        }
        return new ViewModel();
    }

}
