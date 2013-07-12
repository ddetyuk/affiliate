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
        if(!$this->HasIdentity()){
            $this->redirect()->toRoute('home');
        }
        
        $user    = $this->getUser();
        $em      = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form    = $this->getServiceLocator()->get('User\Form\Profile');
        $form->setHydrator(new DoctrineEntity($em, 'User\Model\Entity\User', false));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->populate($form->getData());
                $password = $user->getPassword();
                if (!empty($password)) {
                    $service = $this->getServiceLocator()->get('User\Service\Authentication');
                    $result  = $service->check($user, $form->getData('old_password'));
                    if (!$result->isSuccess()) {
                        foreach ($result->getMessages() as $message) {
                            $this->flashMessenger()->addErrorMessage($message);
                        }
                        return new ViewModel(array('form' => $form));
                    }
                }

                $service = $this->getServiceLocator()->get('User\Service\User');
                $result  = $service->update($user);
                if ($result->isSuccess()) {
                    return $this->redirect()->toRoute('user', array('action' => 'profile'));
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                }
            }
        } else {
            $form->bind($user);
        }
        return new ViewModel(array('form' => $form));
    }

    public function loginAction()
    {
        if($this->HasIdentity()){
            $this->redirect()->toRoute('home');
        }
        
        $user    = new User();
        $form    = $this->getServiceLocator()->get('User\Form\Login');
        $em      = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form->setHydrator(new DoctrineEntity($em, 'User\Model\Entity\User'));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->populate($form->getData());
                $service = $this->getServiceLocator()->get('User\Service\Authentication');
                $result  = $service->login($user);
                if ($result->isSuccess()) {
                    return $this->redirect()->toRoute('home');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Authentication failure.');
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function forgotAction()
    {
        if($this->HasIdentity()){
            $this->redirect()->toRoute('home');
        }
    }

    public function registerAction()
    {
        if($this->HasIdentity()){
            $this->redirect()->toRoute('home');
        }
        
        $user    = new User();
        $form    = $this->getServiceLocator()->get('User\Form\Register');
        $em      = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form->setHydrator(new DoctrineEntity($em, 'User\Model\Entity\User', false));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->populate($form->getData());
                $service = $this->getServiceLocator()->get('User\Service\User');
                $result  = $service->create($user);
                if ($result->isSuccess()) {
                    $auth   = $this->getServiceLocator()->get('User\Service\Authentication');
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
        if(!$this->HasIdentity()){
            $this->redirect()->toRoute('home');
        }
        $auth = $this->getServiceLocator()->get('User\Service\Authentication');
        $auth->logout();
        return $this->redirect()->toRoute('home');
    }

}
