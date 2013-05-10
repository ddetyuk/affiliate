<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_roles")
 */
class Role
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
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="children")
     * @ORM\JoinColumn(name="parentId", referencedColumnName="id")
     * */
    private $parent;

    /**
     * @ORM\ManyToMany(targetEntity="Permission")
     * @ORM\JoinTable(name="user_roles_permissions",
     *      joinColumns={@ORM\JoinColumn(name="roled", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="permissionId", referencedColumnName="id", unique=true)}
     *      )
     **/
    private $permissions;

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }
}