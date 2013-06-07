<?php

namespace User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class IsGranted extends AbstractPlugin
{

    public function __invoke($permission)
    {
        $service = $this->getController()->getServiceLocator()->get('User\Service\Authorization');
        return $service->isGranted($permission);
    }

}