<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_users")
 */
class User {

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
     * @ORM\Column(type="string")
     * @var string
     * @access protected
     */
    protected $reset;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('active', 'inactive')") 
     * @var string
     * @access protected
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     * @var string
     * @access protected
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
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
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
}