<?php

namespace User\Service;

use User\Service\Authentication as AuthenticationService;
use User\Permissions\Rbac\Rbac;

class Authorization
{

    protected $authentication;
    protected $rbac;

    public function __construct(AuthenticationService $service = null, Rbac $rbac = null)
    {
        if (null !== $service) {
            $this->setAuthentication($service);
        }
        if (null !== $rbac) {
            $this->setRbac($rbac);
        }
    }

    public function setAuthentication(AuthenticationService $service)
    {
        $this->authentication = $service;
    }

    public function getAuthentication()
    {
        return $this->authentication;
    }

    public function setRbac(Rbac $rbac)
    {
        $this->rbac = $rbac;
    }

    public function getRbac()
    {
        return $this->rbac;
    }

    public function isGranted($permission)
    {
        $user = $this->getAuthentication()->getIdentity();
        if ($user) {
            $roles = $user->getRoles();
            foreach ($roles as $role) {
                if ($this->getRbac()->isGranted($role, $permission)) {
                    return true;
                }
            }
        }
        return false;
    }

}