<?php

namespace Payment;

use User\Event as UserEvent;

class Event extends UserEvent
{

    const EVENT_CREATE_PAYMENT = 'createpayment';
    const EVENT_CREATE_PAYOUT = 'createpayout';

    protected $amount;

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

}
