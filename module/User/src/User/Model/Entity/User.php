<?php

namespace User\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="User\Model\Repository\User")
 * @ORM\Table(name="user_users")
 */
class User extends AbstractEntity
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
     * @ORM\Column(type="string")
     * @var string
     * @access protected
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     * @var string
     * @access protected
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     * @access protected
     */
    protected $reset;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     * @access protected
     */
    protected $referal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     * @access protected
     */
    protected $balance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     * @access protected
     */
    protected $rate;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     * @access protected
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     * @access protected
     */
    protected $lastname;

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
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_users_roles",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="roleId", referencedColumnName="id", unique=true)}
     *      )
     * */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="UserPage\Model\Entity\Page", mappedBy="user")
     */
    protected $pages;
    
    /**
     * @ORM\OneToMany(targetEntity="Account\Model\Repository\Payout", mappedBy="user")
     */
    protected $payouts;

    /**
     * @ORM\OneToMany(targetEntity="Account\Model\Repository\Payment", mappedBy="user")
     */
    protected $payments;
    
    /**
     * @ORM\OneToMany(targetEntity="Payment\Model\Repository\Transaction", mappedBy="user")
     */
    protected $transactions;
    
    /**
     * @ORM\OneToMany(targetEntity="Contact\Model\Entity\Message", mappedBy="user")
     */
    protected $messages;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

}