<?php

namespace Invite\Service;

use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;

class Invite
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

    public function update($entity)
    {
        $now         = new \DateTime();
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setUser($user);
        $transaction->setType('payment');
        $transaction->setGateway('payza');
        $transaction->setCreated($now);
        $transaction->setUpdated($now);
        $transaction->setStatus('success');
        $transaction->setLogging('success');

        $this->em->persist($transaction);
        $this->em->flush();
    }

    public function send($entity, array $emails)
    {
        $subject = $entity->getSubject();
        $content = $entity->getContent();
        $mailer  = $this->locator->get('Application\Service\Mail');
        $message = $mailer->createHtmlMessage($email, $subject, 'invite/mail/template', array(
            'content' => $content,
            'subject' => $subject,
        ));
        foreach ($emails as $email) {
            
        }
        $result = $mailer->send($message);
        if ($result->isSuccess()) {
            
        } else {
            
        }
    }

}