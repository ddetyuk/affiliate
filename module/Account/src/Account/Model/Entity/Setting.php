<?php

namespace Account\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_settings")
 */
class Setting extends AbstractEntity
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
     * @ORM\Column(type="string", nullable=true)
     * @var string
     * @access protected
     */
    protected $value;

}