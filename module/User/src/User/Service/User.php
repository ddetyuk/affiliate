<?php

namespace User\Service;

use Zend\EventManager\ProvidesEvents;
use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use User\Model\Entity\User as UserEntity;
use User\Event;

class User
{
    use ProvidesEvents;
    
    protected $em;
    protected $entity;
    
    protected $evm;

    public function __construct(EntityManager $em = null, $events = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        if (null !== $events) {
            $this->setEventManager($events);
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
                'fields' => array('email')
        ));
        if ($validator->isValid($user->getEmail())) {
            return new ServiceResult(ServiceResult::FAILURE, $user, array('Email already exists'));
        }
        $validator = new \DoctrineModule\Validator\ObjectExists(array(
                'object_repository' => $this->em->getRepository($this->entity),
                'fields' => array('id')
        ));
        if (!$validator->isValid($user->getReferal())) {
            return new ServiceResult(ServiceResult::FAILURE, $user, array('Referal code not found'));
        }
        $this->em->persist($user);
        $this->em->flush();
        
        $event = new Event(Event::EVENT_CREATE_USER);
        $event->setUser($user);
        $this->getEventManager()->trigger($event);

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function update(UserEntity $user)
    {
        $password = $user->getPassword();
        if (empty($password)) {
            $user->setPassword(null);
        }
        $user->setEmail(null);
        $user->setReferal(null);
        $this->em->merge($user);
        $this->em->flush();

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function delete(UserEntity $user)
    {
        $user->setStatus('inactive');
        $this->update($user);
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