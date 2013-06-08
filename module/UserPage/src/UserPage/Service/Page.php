<?php

namespace UserPage\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use User\Event as UserEvent;
use UserPage\Model\Entity\Page as PageEntity;

class Page implements ListenerAggregateInterface
{

    protected $em;
    protected $entity;
    protected $listeners = array();

    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        $this->entity = 'UserPage\Model\Entity\Page';
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(UserEvent::EVENT_CREATE_USER, array($this, 'onCreateUser'), $priority);
    }

    public function onCreateUser(EventInterface $e)
    {
        $user = $e->getUser();
        try {
            $page = new PageEntity();
            $page->setUser($user);
            $page->setType('page');
            $this->em->persist($page);
            
            
            $this->em->flush();
            
        } catch (\Exception $e) {
            //logging
        }
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

}