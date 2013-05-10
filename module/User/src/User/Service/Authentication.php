<?php

namespace User\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use User\Service\User as UserService;

class Authentication
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
    
    
    public function login($user, $password)
    {
        $service = $this->getAuthenticationService();
        $adapter = $service->getAdapter();
        $adapter->setIdentity($user);
        $adapter->setCredential($password);
        
        $result = $service->authenticate();

        if($result->getCode()==Result::SUCCESS){
            
        }
    }

    public function logout()
    {
        $service = $this->getAuthenticationService();
        $service->clearIdentity();
    }

}