<?php

namespace Contact\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use Contact\Model\Entity\Message;

class Contact
{

    protected $em;
    protected $entity;

    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function send(Message $message)
    {
        $now = new \DateTime();
        $message->setCreated( $now );
        $message->setUpdated( $now );
        
        try {
            $this->em->persist($message);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $message);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return new ServiceResult(ServiceResult::FAILURE, $message, array("Message not sent"));
    }

}

?>
