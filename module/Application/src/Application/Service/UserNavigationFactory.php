<?php

namespace Application\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class UserNavigationFactory extends AbstractNavigationFactory
{
    public function getName()
    {
        return 'user-menu';
    }

}
