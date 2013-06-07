<?php

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class HasIdentity extends AbstractHelper
{

    public function __invoke()
    {
        $service = $this->getServiceLocator()->get('User\Service\Authentication');
        return $service->hasIdentity();
    }

    protected function getServiceLocator()
    {
        return $this->getView()->getHelperPluginManager()->getServiceLocator();
    }

}