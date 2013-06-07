<?php

namespace Account\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\Result as ServiceResult;
use Account\Model\Entity\Setting as SettingEntity;

class Setting
{

    protected $em;
    protected $entity;

    public function __construct(EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
        $this->entity = 'Account\Model\Entity\Setting';
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function get($key)
    {
        try {
            $setting = $this->em->getRepository($this->entity)->findOneByName($key);
            if ($setting) {
                return new ServiceResult(ServiceResult::SUCCESS, $setting);
            }
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
        return new ServiceResult(ServiceResult::FAILURE, null, array('Entity not found'));
    }

    public function set($key, $value)
    {
        $result = $this->get($key);
        if ($result->isSuccess()) {
            $setting = $result->getEntity();
            $setting->setValue($value);
            try {
                $this->em->merge($setting);
                $this->em->flush();
                return new ServiceResult(ServiceResult::SUCCESS, $setting);
            } catch (\Exception $e) {
                return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
            }
        } else {
            $setting = new SettingEntity();
            $setting->setName($key);
            $setting->setValue($value);
            try {
                $this->em->persist($setting);
                $this->em->flush();
                return new ServiceResult(ServiceResult::SUCCESS, $setting);
            } catch (\Exception $e) {
                return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
            }
        }
    }

}

?>
