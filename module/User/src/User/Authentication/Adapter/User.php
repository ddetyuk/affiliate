<?php

namespace User\Authentication\Adapter;

use Zend\Authentication\Result;
use Zend\Authentication\Adapter\AbstractAdapter;
use User\Service\User as UserService;

class User extends AbstractAdapter
{

    public function __construct(UserService $user = null)
    {
        if (null !== $user) {
            $this->setUserService($user);
        }
    }

    public function setUserService(UserService $service)
    {
        $this->userService = $service;
    }

    public function getUserService()
    {
        return $this->userService;
    }

    public function authenticate()
    {
        $password = $this->credential;
        $result   = $this->getUserService()->getUserByEmail($this->identity);
        if ($result->isSuccess()) {
            $entity = $result->getEntity();
            if ($entity->getPassword() == $password) {
                return new Result(Result::SUCCESS, $entity, array('Authentication successful.'));
            }
        }
        return new Result(Result::FAILURE, null, array('Authentication failure.'));
    }

}
