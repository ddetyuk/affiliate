<?php

namespace UserPage\Service;

use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use UserPage\Model\Entity\Page as PageEntity;

class Page
{

    protected $em;
    protected $entity;

    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        $this->entity = 'Invite\Model\Entity\Letter';
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
        if (!$this->IsGranted('userpage.view')) {
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

    public function get($id)
    {
        if (!$this->IsGranted('userpage.get')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        try {
            $page = $this->em->getRepository($this->entity)->findOneById($id);
            return new ServiceResult(ServiceResult::SUCCESS, $page);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function update($content, $user)
    {
        if (!$this->IsGranted('userpage.update')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        try {
            $page = $this->em->getRepository($this->entity)->findOneByUser($user);

            if (!$page) {
                $result = $this->create($user);
                if ($result->isSuccess()) {
                    $page = $result->getEntity();
                }
            }

            $now = new \DateTime();
            $page->setTitle($title);
            $page->setContent($content);
            $page->setUpdated($now);
            $this->em->merge($page);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $page);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

    public function create($user)
    {
        if (!$this->IsGranted('userpage.create')) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ('Access denied'));
        }
        try {
            $now  = new \DateTime();
            $page = new PageEntity();
            $page->setUser($user);
            $page->setCreated($now);
            $page->setUpdated($now);
            $this->em->persist($page);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $page);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array ($e->getMessage()));
        }
    }

}