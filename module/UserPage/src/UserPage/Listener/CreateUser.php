<?php

namespace UserPage\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use User\Event as UserEvent;

class CreateUser implements ListenerAggregateInterface
{

    protected $service;
    protected $listeners;

    public function __construct($service = null)
    {
        if (null !== $service) {
            $this->setPageService($service);
        }
    }

    public function setPageService($service)
    {
        $this->service = $service;
    }

    public function getPageService()
    {
        return $this->service;
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(UserEvent::EVENT_CREATE_USER, array($this, 'onCreateUser'), $priority);
    }

    public function onCreateUser(EventInterface $e)
    {
        
        $result = $this->getPageService()->create($e->getUser());
        if (!$result->isSuccess()) {
            //logging
            var_dump($result->getMessages());
            die;
        }
    }

}