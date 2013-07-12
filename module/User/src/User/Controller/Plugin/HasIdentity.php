<?php

namespace User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class HasIdentity extends AbstractPlugin
{
    public function __invoke()
    {
        $service = $this->getController()->getServiceLocator()->get('User\Service\Authentication');
        return $service->hasIdentity();
    }
}