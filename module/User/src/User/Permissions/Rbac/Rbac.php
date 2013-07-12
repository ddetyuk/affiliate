<?php

namespace User\Permissions\Rbac;

use Zend\Permissions\Rbac\Role;
use Zend\Permissions\Rbac\Rbac as ZendRbac;

class Rbac extends ZendRbac
{

    public function __construct(array $roles = null)
    {
        if (null !== $roles) {
            $this->exchangeArray($roles);
        }
    }

    public function exchangeArray($roles, $root = null)
    {
        foreach ($roles as $k => $v) {
            $role = new Role($k);
            foreach ($v['permissions'] as $permission) {
                $role->addPermission($permission['name']);
            }
            if (array_key_exists('children', $v)) {
                $this->exchangeArray($v['children'], $role);
            }
            if ($root) {
                $root->addChild($role);
            } else {
                $this->addRole($role);
            }
        }
    }

    public function getArrayCopy()
    {
        #TODO
    }

}