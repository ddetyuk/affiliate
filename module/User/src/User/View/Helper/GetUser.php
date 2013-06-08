<?php

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetUser extends AbstractHelper
{

    public function __invoke()
    {
        $service        = $this->getServiceLocator()->get('User\Service\User');
        $authentication = $this->getServiceLocator()->get('User\Service\Authentication');
        $identity       = $authentication->getIdentity();
        $result         = $service->getUserById($identity->getId());
        if ($result->isSuccess()) {
            return $result->getEntity();
        }
    }

    protected function getServiceLocator()
    {
        return $this->getView()->getHelperPluginManager()->getServiceLocator();
    }

}