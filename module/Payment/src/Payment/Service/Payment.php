<?php

namespace Payment\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ProvidesEvents;
use Doctrine\ORM\EntityManager;
use Payment\Model\Entity\Transaction;
use Payment\Event;

class Payment
{

    //use ProvidesEvents;

    protected $em;
    protected $entity;
    protected $events;

    public function __construct(EntityManager $em = null, $events = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        if (null !== $events) {
            $this->setEventManager($events);
        }
        $this->entity = 'Payment\Model\Entity\Transaction';
    }

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }

    public function getEventManager()
    {
        return $this->events;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function payment($user, $amount)
    {
        $now         = new \DateTime();
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setUser($user);
        $transaction->setType('payment');
        $transaction->setGateway('payza');
        $transaction->setCreated($now);
        $transaction->setUpdated($now);
        $transaction->setStatus('success');
        $transaction->setLogging('success');

        $this->em->persist($transaction);
        $this->em->flush();

        $event = new Event(Event::EVENT_CREATE_PAYMENT);
        $event->setTransaction($transaction);
        $this->getEventManager()->trigger($event);
    }

    public function payout($user, $amount)
    {
        $now         = new \DateTime();
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setUser($user);
        $transaction->setType('payout');
        $transaction->setGateway('payza');
        $transaction->setCreated($now);
        $transaction->setUpdated($now);
        $transaction->setStatus('success');
        $transaction->setLogging('success');

        $this->em->persist($transaction);
        $this->em->flush();

        $event = new Event(Event::EVENT_CREATE_PAYOUT);
        $event->setTransaction($transaction);
        $this->getEventManager()->trigger($event);
    }

}