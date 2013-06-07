<?php

namespace Application\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class GuestNavigationFactory extends AbstractNavigationFactory
{

    public function getName()
    {
        return 'guest-menu';
    }

}
