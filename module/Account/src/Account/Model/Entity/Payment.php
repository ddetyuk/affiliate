<?php

namespace Account\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_payments")
 */
class Payment extends AbstractEntity
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
     * @ORM\Column(type="decimal")
     * @var decimal
     * @access protected
     */
    protected $balance;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     * @access protected
     */
    protected $percent;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('active', 'inactive')", options={"default":"active"} ) 
     * @var string
     * @access protected
     */
    protected $status;

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
     * @ORM\ManyToOne(targetEntity="User\Model\Entity\User", inversedBy="payments")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Account\Model\Entity\Repayment", mappedBy="outpayment")
     */
    protected $inrepayments;

    /**
     * @ORM\OneToMany(targetEntity="Account\Model\Entity\Repayment", mappedBy="inpayment")
     */
    protected $outrepayments;

}