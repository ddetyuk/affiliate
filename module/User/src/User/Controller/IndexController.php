<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use User\Model\Entity\User;

class IndexController extends AbstractActionController
{
    
    public function profileAction()
    {
        
        $user = $this->getUser();
        $form = $this->getServiceLocator()->get('User\Form\Profile');
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        //$form->setHydrator(new DoctrineEntity($em, 'User\Model\Entity\User'));
        //$form->bind($user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $message->populate($form->getData());
                $service = $this->getServiceLocator()->get('User\Service\User');
                $result = $service->update($user);
                if ($result->isSuccess()) {
                    //return $this->redirect()->toRoute('home');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                }
            }
        }
        return new ViewModel(array('form' => $form));
    }
    
    public function loginAction()
    {
        $user = new User();
        $form = $this->getServiceLocator()->get('User\Form\Login');
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form->setHydrator(new DoctrineEntity($em, 'User\Model\Entity\User'));
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->populate($form->getData());
                $service = $this->getServiceLocator()->get('User\Service\Authentication');
                $result = $service->login($user);
                if ($result->isSuccess()) {
                    return $this->redirect()->toRoute('home');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                }
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function registerAction()
    {

        $user = new User();
        $form = $this->getServiceLocator()->get('User\Form\Register');
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form->setHydrator(new DoctrineEntity($em, 'User\Model\Entity\User'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->populate($form->getData());
                $service = $this->getServiceLocator()->get('User\Service\User');
                $result = $service->create($user);
                if ($result->isSuccess()) {
                    $auth = $this->getServiceLocator()->get('User\Service\Authentication');
                    $result = $auth->login($user);
                    if ($result->isSuccess()) {
                        return $this->redirect()->toRoute('home');
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
