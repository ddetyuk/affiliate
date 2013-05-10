<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_permissions")
 */
class Permission
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

}