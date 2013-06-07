<?php

namespace Account\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_repayments")
 */
class Repayment extends AbstractEntity
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
     * @ORM\ManyToOne(targetEntity="Account\Model\Entity\Payment", inversedBy="outrepayments")
     * @ORM\JoinColumn(name="inpaymentId", referencedColumnName="id")
     */
    protected $inpayment;

    /**
     * @ORM\ManyToOne(targetEntity="Account\Model\Entity\Payment", inversedBy="inrepayments")
     * @ORM\JoinColumn(name="outpaymentId", referencedColumnName="id")
     */
    protected $outpayment;

}