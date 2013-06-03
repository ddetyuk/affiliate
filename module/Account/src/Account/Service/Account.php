<?php

namespace Account\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Doctrine\ORM\EntityManager;
use User\Event as UserEvent;
use Payment\Event as PaymentEvent;

class Account implements ListenerAggregateInterface
{

    protected $em;
    protected $entity;
    protected $listeners = array();

    /**
     * {@inheritDoc}
     */
    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
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
        $this->listeners[] = $events->attach(PaymentEvent::EVENT_CREATE_PAYMENT, array($this, 'onCreatePayment'), $priority);
        $this->listeners[] = $events->attach(PaymentEvent::EVENT_CREATE_PAYOUT, array($this, 'onCreatePayout'), $priority);
    }

    public function onCreateUser($user)
    {
        //set user balance
        //set user referral
        //update referral rate value
    }

    public function onCreatePayment($user, $amount)
    {
        //create user payment
        //increase system account amount
        //get user referral to repayment
        //create repayment(s)
        //incease referral user balance
        //increase system account percent amount
    }

    public function onCreatePayout($user, $amount)
    {
        //create user payout
        //decrease user balance
        //decrease system account percent amount
    }

}