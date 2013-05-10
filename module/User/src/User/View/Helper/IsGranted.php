<?php

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IsGranted extends AbstractHelper
{
    public function __invoke($permission)
    {
        $service = $this->getServiceLocator()->get( 'User\Service\Authorization' );
        return $service->isGranted($permission);
    }
    
    protected function getServiceLocator()
    {
        return $this->getView()->getHelperPluginManager()->getServiceLocator();
    }
}