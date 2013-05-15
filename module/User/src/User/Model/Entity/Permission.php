<?php

namespace User\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="User\Model\Repository\Permission")
 * @ORM\Table(name="user_permissions")
 */
class Permission extends AbstractEntity
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