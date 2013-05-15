<?php

namespace User\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use User\Model\Entity\User as UserEntity;

class User
{

    protected $em;
    protected $entity;

    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }

        $this->entity = 'User\Model\Entity\User';
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
        $validator = new \DoctrineModule\Validator\ObjectExists(array(
            'object_repository' => $this->em->getRepository($this->entity),
            'fields'            => array('email')
        ));
        if (!$validator->isValid($user->getEmail())) {
            $this->em->persist($user);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $user);
        }else{
            return new ServiceResult(ServiceResult::FAILURE, $user, array('Email already exists'));
        }
    }

    public function update(UserEntity $user)
    {
        $this->em->merge($user);
        $this->em->flush();

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function delete(UserEntity $user)
    {
        $this->em->remove($user);
        $this->em->flush();

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function getUserByEmail($email)
    {
        $user = $this->em->getRepository($this->entity)->findOneByEmail($email);
        if (!is_null($user)) {
            return new ServiceResult(ServiceResult::SUCCESS, $user);
        }
        return new ServiceResult(ServiceResult::FAILURE);
    }
    
    public function getUserById($id)
    {
        $user = $this->em->getRepository($this->entity)->findOneById($id);
        if (!is_null($user)) {
            return new ServiceResult(ServiceResult::SUCCESS, $user);
        }
        return new ServiceResult(ServiceResult::FAILURE);
    }

}