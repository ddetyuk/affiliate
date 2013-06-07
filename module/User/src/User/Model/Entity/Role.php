<?php

namespace User\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="User\Model\Repository\Role")
 * @ORM\Table(name="user_roles")
 */
class Role extends AbstractEntity
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
    protected $name;

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
     * @ORM\OneToMany(targetEntity="Role", mappedBy="parent")
     * */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="children")
     * @ORM\JoinColumn(name="parentId", referencedColumnName="id")
     * */
    protected $parent;

    /**
     * @ORM\ManyToMany(targetEntity="Permission")
     * @ORM\JoinTable(name="user_roles_permissions",
     *      joinColumns={@ORM\JoinColumn(name="roled", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="permissionId", referencedColumnName="id", unique=true)}
     *      )
     * */
    protected $permissions;

    public function __construct()
    {
        $this->children    = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

}