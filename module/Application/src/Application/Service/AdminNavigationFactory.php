<?php

namespace Application\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class AdminNavigationFactory extends AbstractNavigationFactory
{
    public function getName()
    {
        return 'admin-menu';
    }

}
