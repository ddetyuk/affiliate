<?php

namespace Account\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\Paginator\Paginator;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Application\Service\Result as ServiceResult;
use User\Event as UserEvent;
use Payment\Event as PaymentEvent;
use Account\Model\Entity\Payout;
use Account\Model\Entity\Payment;
use Account\Model\Entity\Repayment;
use Account\Model\Entity\Commision;

class Account implements ListenerAggregateInterface
{

    const LEVEL1_PERSENT  = 'account_level1_percent';
    const LEVEL2_PERSENT  = 'account_level2_percent';
    const LEVEL3_PERSENT  = 'account_level3_percent';
    const USERS_BALANCE   = 'account_system_balance';
    const SYSTEM_BALANCE  = 'account_users_balance';
    const PAYMENT_BALANCE = 'account_payment_balance';
    const USERS_MIN_VAL   = 'account_users_minval';

    protected $em;
    protected $settingManager;
    protected $entity    = 'User\Model\Entity\User';
    protected $listeners = array ();

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
        $this->listeners[] = $events->attach(UserEvent::EVENT_CREATE_USER, array ($this, 'onCreateUser'), $priority);
        $this->listeners[] = $events->attach(PaymentEvent::EVENT_CREATE_PAYMENT, array ($this, 'onCreatePayment'), $priority);
        $this->listeners[] = $events->attach(PaymentEvent::EVENT_CREATE_PAYOUT, array ($this, 'onCreatePayout'), $priority);
    }

    public function onCreateUser(EventInterface $e)
    {
        $user = $e->getUser();
        try {
            //update referral rate value
            $referral = $this->em->getRepository($this->entity)->findOneById($user->getReferral());
            $referral->setRate($referral->getRate() + 1);

            $this->em->merge($user);
            $this->em->merge($referral);
            $this->em->flush();
        } catch (\Exception $e) {
            //logging
        }
    }

    public function onCreatePayment(EventInterface $e)
    {
        $transaction = $e->getTransaction();
        $user        = $transaction->getUser();
        $amount      = $transaction->getAmount();

        $product = $this->getProductByAmount($amount);

        $min = $this->getConfigValue(self::USERS_MIN_VAL, 0.6);

        //create user payment
        try {
            $now     = new \DateTime();
            $payment = new Payment();
            $payment->setAmount($amount);
            $payment->setBalance($amount * $min);
            $payment->setPercent($product->getRate());
            $payment->setProduct($product);
            $payment->setStatus('active');
            $payment->setUser($user);
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
            //create commision payment
            $percent1 = $amount * $product->getLevel1();
            $this->createCommisionPayment($referral1, $payment, $percent1);
            //create repayment payment
            $rpercent1 = $amount * $this->getConfigValue(self::LEVEL1_PERSENT, 0.12);
            $this->createRepaymentPayment($referral1, $payment, $rpercent1);

            $this->updateUserPayments($referral1);
        }

        //get user referral to referral commision level 2
        $referral2 = $this->em->getRepository($this->entity)->getReferral($user, 2);
        if ($referral2) {
            $percent2 = $amount * $product->getLevel2();
            $this->createCommisionPayment($referral2, $payment, $percent2);
            //create repayment payment
            $rpercent2 = $amount * $this->getConfigValue(self::LEVEL2_PERSENT, 0.06);
            $this->createRepaymentPayment($referral2, $payment, $rpercent2);

            $this->updateUserPayments($referral2);
        }

        //get user referral to referral commision level 3
        $referral3 = $this->em->getRepository($this->entity)->getReferral($user, 3);
        if ($referral3) {
            $percent3 = $amount * $product->getLevel3();
            $this->createCommisionPayment($referral3, $payment, $percent3);
            //create repayment payment
            $rpercent3 = $amount * $this->getConfigValue(self::LEVEL3_PERSENT, 0.03);
            $this->createRepaymentPayment($referral3, $payment, $rpercent3);

            $this->updateUserPayments($referral3);
        }

        //increase system account percent amount
        $usersBalance += $this->getConfigValue(self::USERS_BALANCE, 0);
        $this->setConfigValue(self::USERS_BALANCE, $usersBalance);
    }

    public function onCreatePayout(EventInterface $e)
    {
        $transaction = $e->getTransaction();
        $user        = $transaction->getUser();
        $amount      = $transaction->getAmount();
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
                $this->em->persist($payout);
                $this->em->merge($user);
                $this->em->flush();

                //decrease system account percent amount
                $usersBalance = $this->getConfigValue(self::USERS_BALANCE, 0) - $amount;
                $this->setConfigValue(self::USERS_BALANCE, $usersBalance);
            } catch (\Exception $e) {
                //logging
                echo $e->getMessage();
                return false;
            }
        }
    }

    protected function createCommisionPayment($user, $payment, $amount)
    {
        try {
            $this->em->beginTransaction();

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

            $this->em->commit();

            //increase system account percent amount
            $amount += $this->getConfigValue(self::USERS_BALANCE, 0);
            $this->setConfigValue(self::USERS_BALANCE, $amount);
        } catch (\Exception $e) {
            $this->em->rollback();
            return false;
        }
        return true;
    }

    protected function createRepaymentPayment($user, $inPayment, $amount)
    {
        $now      = new \DateTime();
        $payments = $user->getPayments();
        if ($payments) {
            foreach ($payments as $payment) {
                if ($payment->getStatus() == 'active') {
                    $profit = $payment->getAmount() * $payment->getPercent();
                    if ($profit <= $payment->getBalance()) {
                        //completed
                        continue;
                    }
                    $term = $payment->getProduct()->getTerm() * 86400;
                    if ($now->getTimestamp() - $payment->getCteated()->getTimestamp() > $term) {
                        //expired
                        continue;
                    }
                    if ($profit - $payment->getBalance() <= $amount) {
                        $amount = $profit - $payment->getBalance();
                    }
                    //create repayment
                    try {
                        $now       = new \DateTime();
                        $repayment = new Repayment();
                        $repayment->setAmount($amount);
                        $repayment->setCreated($now);
                        $repayment->setUpdated($now);
                        $repayment->setInpayment($inPayment);
                        $repayment->setOutpayment($payment);
                        $this->em->persist($repayment);

                        //incease payment balance
                        $payment->setBalance($payment->getBalance() + $amount);
                        $this->em->merge($payment);
                        $this->em->flush();

                        //increase payment balance
                        $balance = $this->getConfigValue(self::PAYMENT_BALANCE, 0);
                        $this->setConfigValue(self::PAYMENT_BALANCE, $balance + $amount);
                        
                    } catch (\Exception $e) {
                        //logging
                    }
                    break;
                }
            }
        }

        return false;
    }

    protected function updateUserPayments($user)
    {
        $amount   = 0;
        $now      = new \DateTime();
        $payments = $user->getPayments();
        if ($payments) {
            foreach ($payments as $payment) {
                if ($payment->getStatus() == 'active') {
                    $profit = $payment->getAmount() * $payment->getPercent();
                    if ($profit <= $payment->getBalance()) {
                        try {
                            $this->em->beginTransaction();

                            $payment->setStatus('inactive');
                            $payment->setUpdated($now);
                            $this->em->merge($payment);

                            $user->setBalance($user->getBalance() + $payment->getBalance());
                            $this->em->merge($user);

                            $this->em->commit();
                            $amount += $payment->getBalance();
                        } catch (\Exception $e) {
                            $this->em->rollback();
                        }
                    }
                    $term = $payment->getProduct()->getTerm() * 86400;
                    if ($now->getTimestamp() - $payment->getCteated()->getTimestamp() > $term) {
                        try {
                            $payment->setStatus('expired');
                            $payment->setUpdated($now);
                            $this->em->merge($payment);
                            $this->em->flush();
                        } catch (\Exception $e) {
                            //logging
                        }
                    }
                }
            }
        }

        //increase system account percent amount
        $balance = $this->getConfigValue(self::USERS_BALANCE, 0);
        $this->setConfigValue(self::USERS_BALANCE, $balance + $amount);

        //decrease payment balance
        $balance = $this->getConfigValue(self::PAYMENT_BALANCE, 0);
        $this->setConfigValue(self::PAYMENT_BALANCE, $balance - $amount);

        return $amount;
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

    public function getPaymentsPaginator()
    {
        try {
            $query     = $this->em->getRepository('Account\Model\Entity\Payment')->createQueryBuilder('p');
            $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
            return new ServiceResult(ServiceResult::SUCCESS, $paginator);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function getRepaymentsPaginator()
    {
        try {
            $query     = $this->em->getRepository('Account\Model\Entity\Repayment')->createQueryBuilder('p');
            $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
            return new ServiceResult(ServiceResult::SUCCESS, $paginator);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function getCommisionsPaginator()
    {
        try {
            $query     = $this->em->getRepository('Account\Model\Entity\Commision')->createQueryBuilder('p');
            $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
            return new ServiceResult(ServiceResult::SUCCESS, $paginator);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function getPayoutsPaginator()
    {
        try {
            $query     = $this->em->getRepository('Account\Model\Entity\Payout')->createQueryBuilder('p');
            $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
            return new ServiceResult(ServiceResult::SUCCESS, $paginator);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function getProductByAmount($amount)
    {
        $qb = $this->em->getRepository('Account\Model\Entity\Product')->createQueryBuilder('p');
        $qb->where($qb->expr()->andX(
            $qb->expr()->gte('p.minamount', $amount),
            $qb->expr()->gt('p.maxamount', $amount)
        ));
        return $qb->getQuery()->getResult();
    }

}

