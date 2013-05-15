<?php

namespace UserPage\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\AbstractEntity;
use User\Model\Entity\User;

/**
 * @ORM\Entity(repositoryClass="UserPage\Model\Repository\Page")
 * @ORM\Table(name="userpage_pages")
 */
class Page extends AbstractEntity
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
    protected $title;

    /**
     * @ORM\Column(type="string")
     * @var string
     * @access protected
     */
    protected $content;
    
    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('page', 'email')", options={"default":"page"} ) 
     * @var string
     * @access protected
     */
    protected $type;
    
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
     * @ORM\ManyToOne(targetEntity="User\Model\Entity\User", inversedBy="pages")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;
    
}