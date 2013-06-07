<?php

namespace Contact\Service;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Zend\Paginator\Paginator;
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
        $this->entity = 'Contact\Model\Entity\Message';
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function getPaginator($params = null)
    {
        try {
            $query     = $this->em->getRepository($this->entity)->createQueryBuilder('p');
            $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
            return new ServiceResult(ServiceResult::SUCCESS, $paginator);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
    }

    public function get($id)
    {
        try {
            $message = $this->em->getRepository($this->entity)->findOneById($id);
            return new ServiceResult(ServiceResult::SUCCESS, $message);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
    }

    public function create(Message $message)
    {
        $now = new \DateTime();
        $message->setCreated($now);
        $message->setUpdated($now);

        try {
            $this->em->persist($message);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $message);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, $message, array($e->getMessage()));
        }
    }

    public function update(Message $message)
    {
        $now = new \DateTime();
        $message->setUpdated($now);

        try {
            $this->em->merge($message);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $message);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, $message, array($e->getMessage()));
        }
    }

    public function delete(Message $message)
    {
        try {
            $this->em->delete($message);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $message);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, $message, array($e->getMessage()));
        }
    }

}

?>
