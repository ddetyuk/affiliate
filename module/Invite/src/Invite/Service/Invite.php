<?php

namespace Invite\Service;

use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use Invite\Model\Entity\Letter;


class Invite
{

    protected $em;
    protected $entity;
    protected $mailer;
    protected $renderer;

    public function __construct(EntityManager $em = null, $mailer = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        if (null !== $mailer) {
            $this->setMailService($mailer);
        }
        $this->entity = 'Invite\Model\Entity\Letter';
    }

    public function setMailService($mailer)
    {
        $this->mailer = $mailer;
    }

    public function getMailService()
    {
        return $this->mailer;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    protected function getRenderer()
    {
        return $this->renderer;
    }

    protected function setRenderer($renderer)
    {
        $this->renderer = $renderer;
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

    public function update($subject, $content, $user)
    {
        try {
            $letter = $this->em->getRepository($this->entity)->findOneByUserId($user->getId());

            if (!$letter) {
                $result = $this->create($user);
                if ($result->isSuccess()) {
                    $letter = $result->getEntity();
                }
            }

            $now = new \DateTime();
            $letter->setSubject($subject);
            $letter->setContent($content);
            $letter->setUpdated($now);
            $this->em->merge($letter);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $letter);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
    }

    public function create($user)
    {
        try {
            $now    = new \DateTime();
            $letter = new Letter();
            $letter->setUser($user);
            $letter->setInvited(0);
            $letter->setIsdefault(1);
            $letter->setCreated($now);
            $letter->setUpdated($now);
            $this->em->persist($letter);
            $this->em->flush();
            return new ServiceResult(ServiceResult::SUCCESS, $letter);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
    }

    public function send(array $emails, $user)
    {
        try {
            $letter = $this->em->getRepository($this->entity)->findOneByUserId($user->getId());
            if ($letter->getIsdefault()) {
                $content = $this->getDefaultContent();
                $subject = $this->getDefaultSubject();
            } else {
                $content = $this->getContent();
                $subject = $this->getSubject();
            }
            $mailer  = $this->getMailService();
            $message = $mailer->createHtmlMessage('invite/mail/template', array(
                'content' => $content,
                'subject' => $subject,
            ));
            foreach ($emails as $email) {
                $message->setTo($email);
                $result = $mailer->send($message);
                if (!$result->isSuccess()) {
                    //logging
                } else {
                    $letter->setInvited($letter->getInvited() + 1);
                    $this->em->merge($letter);
                    $this->em->flush();
                }
            }
            return new ServiceResult(ServiceResult::SUCCESS);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
    }

    public function getDefaultContent()
    {
        $model    = new ViewModel();
        $model->setTemplate('invite/mail/content');
        $renderer = $this->getRenderer();
        return $renderer->render($model);
    }

    public function getDefaultSubject()
    {
        return 'Hello';
    }

}