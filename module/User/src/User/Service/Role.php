<?php

namespace User\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use User\Model\Entity\User as UserEntity;

class Role
{

    protected $em;
    protected $entity;

    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }

        $this->entity = 'User\Model\Entity\Role';
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function create(UserEntity $user)
    {
        $this->em->persist($user);
        $this->em->flush();

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function update(UserEntity $user)
    {
        $this->em->persist($user);
        $this->em->flush();

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function delete(UserEntity $user)
    {
        $this->em->remove($user);
        $this->em->flush();

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function getAll()
    {
        $collection = $this->em->getRepository($this->entity)->findAll();
        if (!is_null($collection)) {
            return new ServiceResult(ServiceResult::SUCCESS, $collection);
        }
        return new ServiceResult(ServiceResult::FAILURE);
    }

}