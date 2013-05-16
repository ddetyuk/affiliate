<?php

namespace Contact\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_messages")
 */
class Message extends AbstractEntity
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
    protected $subject;

    /**
     * @ORM\Column(type="string")
     * @var string
     * @access protected
     */
    protected $message;

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
     * @ORM\ManyToOne(targetEntity="User\Model\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

}