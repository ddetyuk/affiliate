<?php

namespace User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetUser extends AbstractPlugin
{

    public function __invoke()
    {
        $authentication = $this->getController()->getServiceLocator()->get('User\Service\Authentication');
        $identity       = $authentication->getIdentity();
        $service        = $this->getController()->getServiceLocator()->get('User\Service\User');
        $result         = $service->getUserById($identity->getId());
        if ($result->isSuccess()) {
            $user = $result->getEntity();
            $user->setPassword('');
            return $user;
        }
    }

}