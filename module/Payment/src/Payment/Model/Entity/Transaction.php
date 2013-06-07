<?php

namespace Payment\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment_transactions")
 */
class Transaction extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     * @access protected
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var decimal
     * @access protected
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('success', 'fail')", options={"default":"success"} ) 
     * @var string
     * @access protected
     */
    protected $status;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('payment', 'payout')", options={"default":"payment"} ) 
     * @var string
     * @access protected
     */
    protected $type;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('payza', 'paypal')", options={"default":"payza"} ) 
     * @var string
     * @access protected
     */
    protected $gateway;

    /**
     * @ORM\Column(type="string") 
     * @var string
     * @access protected
     */
    protected $logging;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var string
     * @access protected
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var string
     * @access protected
     */
    protected $updated;

    /**
     * @ORM\ManyToOne(targetEntity="User\Model\Entity\User", inversedBy="transactions")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

}