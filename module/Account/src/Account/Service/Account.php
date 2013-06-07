<?php

namespace Account\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Doctrine\ORM\EntityManager;
use User\Event as UserEvent;
use Payment\Event as PaymentEvent;
use Account\Model\Entity\Payout;
use Account\Model\Entity\Payment;
use Account\Model\Entity\Repayment;
use Account\Model\Entity\Commision;

class Account implements ListenerAggregateInterface
{

    const LEVEL1_PERSENT = 'account_level1_percent';
    const LEVEL2_PERSENT = 'account_level2_percent';
    const LEVEL4_PERSENT = 'account_level3_percent';
    const USERS_BALANCE  = 'account_system_balance';
    const SYSTEM_BALANCE = 'account_users_balance';

    protected $em;
    protected $settingManager;
    protected $entity    = 'User\Model\Entity\User';
    protected $listeners = array();

    /**
     * {@inheritDoc}
     */
    public function __construct(EntityManager $em = null, $settingManager = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        if (null !== $settingManager) {
            $this->setSettingManager($settingManager);
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

    public function getSettingManager()
    {
        return $this->settingManager;
    }

    public function setSettingManager($settingManager)
    {
        $this->settingManager = $settingManager;
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

    public function getUserPercent($user)
    {
        $user->getRate();
        return .1;
    }

    public function onCreateUser($user)
    {
        try {
            //set user balance
            $user->setBalance(0);
            $user->setRate(0);
            //update referral rate value
            $referral = $user->getReferral();
            $referral->setRate($referral->getRate() + 1);

            $this->em->merge($user);
            $this->em->merge($referral);
            $this->em->flush();
        } catch (\Exception $e) {
            //logging
        }
    }

    public function onCreatePayment($e)
    {
        $transaction = $e->setTransaction();
        $user        = $transaction->getUser();
        $amount      = $transaction->getAmount();

        //create user payment
        try {
            $now     = new \DateTime();
            $payment = new Payment();
            $payment->setAmount($amount);
            $payment->setBalance(0);
            $payment->setPercent($this->getUserPercent($user));
            $payment->setStatus('active');
            $payment->setCreated($now);
            $payment->setUpdated($now);

            $this->em->persist($payment);
            $this->em->flush();
        } catch (\Exception $e) {
            //logging
            return false;
        }

        $usersBalance = 0;

        //increase system account amount
        $systemBalance = $this->getConfigValue(self::SYSTEM_BALANCE, 0);
        $this->setConfigValue(self::SYSTEM_BALANCE, $systemBalance + $amount);

        //get user referral to referral commision level 1
        $referral1 = $this->em->getRepository($this->entity)->getReferral($user, 1);
        if ($referral1) {
            $percent1 = $amount * $this->getConfigValue(self::LEVEL1_PERSENT, 0.10);
            if ($this->createCommisionPayment($referral1, $payment, $percent1)) {
                $usersBalance += $percent1;
            }
        }

        //get user referral to referral commision level 2
        $referral2 = $this->em->getRepository($this->entity)->getReferral($user, 2);
        if ($referral2) {
            $percent2 = $amount * $this->getConfigValue(self::LEVEL2_PERSENT, 0.05);
            if ($this->createCommisionPayment($referral2, $payment, $percent2)) {
                $usersBalance += $percent2;
            }
        }

        //get referal payments
        $referral4 = $this->em->getRepository($this->entity)->getReferral($user, 4);
        if ($referral4) {
            $payments = $referral4->getPayments();
            if ($payments) {
                $percent4 = $amount * $this->getConfigValue(self::LEVEL4_PERSENT, 0.05);
                foreach ($payments as $rpayment) {
                    if ($rpayment->getStatus() == 'active') {
                        $profit = $rpayment->getAmount() * $rpayment->getPercent();
                        if ($profit <= $rpayment->getBalance()) {
                            $rpayment->setStatus('inactive');
                            continue;
                        }
                        if ($profit - $rpayment->getBalance() <= $percent4) {
                            $percent4 = $profit - $rpayment->getBalance();
                        }
                        //create repayment
                        if ($this->createRepaymentPayment($user, $payment, $rpayment, $percent4)) {
                            $usersBalance += $percent4;
                        }
                        break;
                    }
                }
            }
        }

        //increase system account percent amount
        $usersBalance += $this->getConfigValue(self::USERS_BALANCE, 0);
        $this->setConfigValue(self::USERS_BALANCE, $usersBalance);
    }

    public function onCreatePayout($user, $amount)
    {
        if ($user->getBalance() >= $amount) {
            try {
                $now    = new \DateTime();
                //create user payout
                $payout = new Payout();
                $payout->setUser($user);
                $payout->setAmount($amount);
                $payout->setCreated($now);
                $payout->setUpdated($now);

                //decrease user balance
                $user->setBalance($user->getBalance() - $amount);
                $this->em->merge($user);
                $this->em->flush();

                //decrease system account percent amount
                $usersBalance = $this->getConfigValue(self::USERS_BALANCE, 0) - $amount;
                $this->setConfigValue(self::USERS_BALANCE, $usersBalance);
            } catch (\Exception $e) {
                //logging
                return false;
            }
        }
    }

    protected function createCommisionPayment($user, $payment, $amount)
    {
        try {
            $now       = new \DateTime();
            $commision = new Commision();
            $commision->setAmount($amount);
            $commision->setPayment($payment);
            $commision->setUser($user);
            $commision->setCreated($now);
            $commision->setUpdated($now);
            $this->em->persist($commision);

            //incease user balance
            $user->setBalance($user->getBalance() + $amount);
            $this->em->merge($user);
            $this->em->flush();
        } catch (\Exception $e) {
            //logging
            return false;
        }
        return true;
    }

    protected function createRepaymentPayment($user, $inPayment, $outPayment, $amount)
    {
        try {
            $now       = new \DateTime();
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
            $user->setBalance($user->getBalance() + $amount);
            $this->em->merge($user);
            $this->em->flush();
        } catch (\Exception $e) {
            //logging
            return false;
        }
        return true;
    }

    protected function getConfigValue($key, $default)
    {
        $result = $this->getSettingManager()->get($key);
        if ($result->isSuccess()) {
            return $result->getEntity()->getValue();
        }
        return $default;
    }

    protected function setConfigValue($key, $value)
    {
        $result = $this->getSettingManager()->set($key, $value);
        if ($result->isSuccess()) {
            return true;
        }
        return false;
    }

}