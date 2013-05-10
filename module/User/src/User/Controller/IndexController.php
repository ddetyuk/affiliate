<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

use User\Entity\User;

class IndexController extends AbstractActionController
{

    public function loginAction()
    {
        $user = new User();
        $form = $this->getServiceLocator()->get('User\Form\Login');
        $form->bind($user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $auth = $this->getServiceLocator()->get('User\Service\Authentication');
                $auth->login($user);
                return $this->redirect()->toRoute('home');
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function registerAction()
    {
        
        $user = new User();
        $form = $this->getServiceLocator()->get('User\Form\Register');
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form->setHydrator(new DoctrineEntity($em, 'User\Entity\User'));
        $form->setBindOnValidate(false);
        $form->bind($user);
            
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get('User\Service\User');
                $service->create($user);

                $auth = $this->getServiceLocator()->get('User\Service\Authentication');
                $auth->login($user);
                return $this->redirect()->toRoute('home');
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('User\Service\Authentication');
        $auth->logout();
        return $this->redirect()->toRoute('home');
    }

}
