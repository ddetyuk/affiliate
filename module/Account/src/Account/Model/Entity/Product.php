<?php

namespace Account\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_products")
 */
class Product extends AbstractEntity
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
     * @ORM\Column(type="string", columnDefinition="ENUM('long', 'short')", options={"default":"short"} ) 
     * @var string
     * @access protected
     */
    protected $type;
    
    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var decimal
     * @access protected
     */
    protected $minamount;
    
    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var decimal
     * @access protected
     */
    protected $maxamount;

    /**
     * @ORM\Column(type="decimal")
     * @var decimal
     * @access protected
     */
    protected $term;

    /**
     * @ORM\Column(type="decimal", scale=3)
     * @var decimal
     * @access protected
     */
    protected $rate;
    
    /**
     * @ORM\Column(type="decimal", scale=3)
     * @var decimal
     * @access protected
     */
    protected $daily;

    /**
     * @ORM\Column(type="decimal", scale=3)
     * @var decimal
     * @access protected
     */
    protected $level1;
    
    /**
     * @ORM\Column(type="decimal", scale=3)
     * @var decimal
     * @access protected
     */
    protected $level2;
    
    /**
     * @ORM\Column(type="decimal", scale=3)
     * @var decimal
     * @access protected
     */
    protected $level3;
    
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
     * @ORM\OneToMany(targetEntity="Account\Model\Entity\Payment", mappedBy="product")
     */
    protected $payments;


}