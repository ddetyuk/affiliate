<?php

namespace Payment;

use Zend\EventManager\Event as ZendEvent;
use Payment\Model\Entity\Transaction;

class Event extends ZendEvent
{

    const EVENT_CREATE_PAYMENT = 'createpayment';
    const EVENT_CREATE_PAYOUT  = 'createpayout';

    protected $transaction;

    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

}
