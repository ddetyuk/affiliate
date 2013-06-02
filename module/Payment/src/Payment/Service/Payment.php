<?php

namespace Payment\Service;

use Zend\EventManager\ProvidesEvents;
use Doctrine\ORM\EntityManager;
use Payment\Event;

class Payment
{

    use ProvidesEvents;

    protected $em;
    protected $entity;

    public function __construct(EntityManager $em = null, $events = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        if (null !== $events) {
            $this->setEventManager($events);
        }
        $this->entity = 'Payment\Model\Entity\Payment';
    }

    public function payment($user, $amount)
    {
        $event = new Event(Event::EVENT_CREATE_PAYMENT);
        $event->setUser($user);
        $event->setAmount($amount);
        $this->getEventManager()->trigger($event);
    }

    public function payout($user, $amount)
    {
        $event = new Event(Event::EVENT_CREATE_PAYOUT);
        $event->setUser($user);
        $event->setAmount($amount);
        $this->getEventManager()->trigger($event);
    }

}