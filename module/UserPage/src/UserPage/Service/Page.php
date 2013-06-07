<?php

namespace UserPage\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use User\Service\User as UserService;
use Application\Service\Result as ServiceResult;
use User\Model\Entity\User as UserEntity;

class UserPage
{

    protected $authenticationService;
    protected $userService;

    public function __construct(AuthenticationService $service = null, UserService $user = null)
    {
        if (null !== $service) {
            $this->setAuthenticationService($service);
        }
        if (null !== $user) {
            $this->setUserService($user);
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

    public function setUserService(UserService $service)
    {
        $this->userService = $service;
    }

    public function getUserService()
    {
        return $this->userService;
    }

    public function encodePassword($password)
    {
        return $password;
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