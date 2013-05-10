<?php

namespace User\Service;

use User\Service\Authentication as AuthenticationService;
use User\Service\User as UserService;

class Authorization
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
    
    public function isGranted()
    {
        
    }

}