<?php

namespace Account\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Doctrine\ORM\EntityManager;
use User\Event as UserEvent;
use Payment\Event as PaymentEvent;
use Account\Model\Entity\Payment;
use Account\Model\Entity\Repayment;
use Account\Model\Entity\Commision;

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

    public function onCreatePayment($e)
    {
        $transaction = $e->setTransaction();
        $transaction->getUser();

        //create user payment
        $now = new \DateTime();
        $payment = new Payment();
        $payment->setAmount($transaction->getAmount());
        $payment->setBalance(0);
        $payment->setPercent();
        $payment->setStatus('active');
        $payment->setCreated($now);
        $payment->setUpdated($now);

        $this->em->persist($payment);
        $this->em->flush();

        //increase system account amount
        
        //get user referral to referral commision level 1
        $this->createCommisionPayment($user, $payment, $amount);

        //get user referral to referral commision level 2
        $this->createCommisionPayment($user, $payment, $amount);
        
        //get user referral to repayment level 4
        //get referal payments
        
        //create repayment(s)
        $this->createRepaymentPayment($user, $inPayment, $outPayment, $amount);

        $this->em->flush();

        //increase system account percent amount
    }

    protected function createCommisionPayment($user, $payment, $amount)
    {
        $now = new \DateTime();
        $commision = new Commision();
        $commision->setAmount($amount);
        $commision->setPayment($payment);
        $commision->setUser($user);
        $commision->setCreated($now);
        $commision->setUpdated($now);
        $this->em->persist($commision);

        //incease user balance
        $user = $user->getUser();
        $user->setBalance($user->getBalance() + $amount);
        $this->em->merge($user);
    }

    protected function createRepaymentPayment($user, $inPayment, $outPayment, $amount)
    {
        $now = new \DateTime();
        $repayment = new Repayment();
        $repayment->setAmount($amount);
        $repayment->setCreated($now);
        $repayment->setUpdated($now);
        $repayment->setInPayment($inPayment);
        $repayment->setOutPayment($outPayment);
        $this->em->persist($repayment);
        
        //incease payment balance
        $outPayment->setBalance($outPayment->getBalance() + $amount);
        $this->em->merge($outPayment);

        //incease user balance
        $user = $user->getUser();
        $user->setBalance($user->getBalance() + $amount);
        $this->em->merge($user);
    }
    
    public function onCreatePayout($user, $amount)
    {
        //create user payout
        //decrease user balance
        //decrease system account percent amount
    }

}