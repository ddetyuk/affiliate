<?php

namespace Invite\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="invite_letters")
 */
class Letter extends AbstractEntity
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
    protected $content;

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
     * @ORM\OneyToOne(targetEntity="User\Model\Entity\User", inversedBy="letter")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

}