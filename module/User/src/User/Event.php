<?php

namespace User;

use Zend\EventManager\Event as ZendEvent;
use User\Model\Entity\User;

class Event extends ZendEvent
{

    const EVENT_CREATE_USER = 'createuser';

    protected $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

}
