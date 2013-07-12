<?php

namespace User\Service;

use Zend\Paginator\Paginator;
use Zend\EventManager\EventManagerInterface;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use User\Model\Entity\User as UserEntity;
use User\Event;

class User
{

    protected $events;
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

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }

    public function getEventManager()
    {
        return $this->events;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function IsGranted($permission)
    {
        return true;
    }

    public function getPaginator($params = null)
    {
        if (!$this->IsGranted('user.view')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        try {
            $query     = $this->em->getRepository($this->entity)->createQueryBuilder('p');
            $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
            return new ServiceResult(ServiceResult::SUCCESS, $paginator);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function create(UserEntity $user)
    {
        if (!$this->IsGranted('user.create')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        $validator = new \DoctrineModule\Validator\ObjectExists(array (
                    'object_repository' => $this->em->getRepository($this->entity),
                    'fields'            => array ('email')
                ));
        if ($validator->isValid($user->getEmail())) {
            return new ServiceResult(ServiceResult::FAILURE, $user, array ('Email already exists'));
        }
        $validator = new \DoctrineModule\Validator\ObjectExists(array (
                    'object_repository' => $this->em->getRepository($this->entity),
                    'fields'            => array ('id')
                ));
        if (!$validator->isValid($user->getReferral())) {
            return new ServiceResult(ServiceResult::FAILURE, $user, array ('Referal code not found'));
        }
        $now = new \DateTime();
        $user->setCreated($now);
        $user->setUpdated($now);
        $user->setStatus('active');
        $user->setBalance(0);
        $user->setRate(0);
        $user->setReset(''); #FIXME:

        $this->em->persist($user);
        $this->em->flush();

        $event = new Event(Event::EVENT_CREATE_USER);
        $event->setUser($user);
        $this->getEventManager()->trigger($event);

        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function update(UserEntity $user)
    {
        if (!$this->IsGranted('user.update')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
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
        if (!$this->IsGranted('user.delete')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        $user->setStatus('inactive');
        $this->update($user);
        return new ServiceResult(ServiceResult::SUCCESS, $user);
    }

    public function getUserByEmail($email)
    {
        if (!$this->IsGranted('user.get')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        $user = $this->em->getRepository($this->entity)->findOneByEmail($email);
        if (!is_null($user)) {
            return new ServiceResult(ServiceResult::SUCCESS, $user);
        }
        return new ServiceResult(ServiceResult::FAILURE);
    }

    public function getUserById($id)
    {
        if (!$this->IsGranted('user.get')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        $user = $this->em->getRepository($this->entity)->findOneById($id);
        if (!is_null($user)) {
            return new ServiceResult(ServiceResult::SUCCESS, $user);
        }
        return new ServiceResult(ServiceResult::FAILURE);
    }

}