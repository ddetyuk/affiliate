<?php

namespace User\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Application\Service\Result as ServiceResult;
use User\Model\Entity\User as UserEntity;

class Authentication
{

    protected $authenticationService;

    public function __construct(AuthenticationService $service = null)
    {
        if (null !== $service) {
            $this->setAuthenticationService($service);
        }
    }

    public function setAuthenticationService(AuthenticationService $service)
    {
        $this->authenticationService = $service;
    }

    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    public function encodePassword($password)
    {
        for ($i = 0; $i < 25; $i++) {
            $password = hash( "sha256", $password);
        }
        return $password;
    }

    public function check(UserEntity $user, $password)
    {
        $username = $user->getEmail();
        $password = $this->encodePassword($password);
        $service  = $this->getAuthenticationService();
        $adapter  = $service->getAdapter();
        $adapter->setIdentity($username);
        $adapter->setCredential($password);

        $result = $adapter->authenticate();

        if ($result->getCode() == Result::SUCCESS) {
            return new ServiceResult(ServiceResult::SUCCESS);
        }

        return new ServiceResult(ServiceResult::FAILURE, null, $result->getMessages());
    }

    public function login(UserEntity $user)
    {
        $username = $user->getEmail();
        $password = $this->encodePassword($user->getPassword());

        $service  = $this->getAuthenticationService();
        $adapter  = $service->getAdapter();
        $adapter->setIdentity($username);
        $adapter->setCredential($password);

        $result = $service->authenticate();

        if ($result->getCode() == Result::SUCCESS) {
            return new ServiceResult(ServiceResult::SUCCESS);
        }

        return new ServiceResult(ServiceResult::FAILURE, null, $result->getMessages());
    }

    public function logout()
    {
        $service = $this->getAuthenticationService();
        $service->clearIdentity();
        return new ServiceResult(ServiceResult::SUCCESS);
    }

    public function hasIdentity()
    {
        return $this->getAuthenticationService()->hasIdentity();
    }

    public function getIdentity()
    {
        return $this->getAuthenticationService()->getIdentity();
    }

}