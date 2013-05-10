<?php

namespace User\Service;

use Doctrine\ORM\EntityManager;
use User\Entity\User as UserEntity;

class User
{

    protected $em;

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

    public function create(UserEntity $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function update(UserEntity $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(UserEntity $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

}