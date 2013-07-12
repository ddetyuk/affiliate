<?php

namespace User\Collector;

use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\CollectorInterface;
use User\Service\Authorization;

class RoleCollector implements CollectorInterface
{
    /**
     * Collector priority
     */

    const PRIORITY = 10;
    
    protected $roles = null;

    public function __construct(Authorization $authorization)
    {
        $user = $authorization->getAuthentication()->getIdentity();
        if($user){
            $this->roles = $user->getRoles();
        }
    }

    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * Collector Name.
     *
     * @return string
     */
    public function getName()
    {
        return 'roles';
    }

    /**
     * Collector Priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return self::PRIORITY;
    }

    /**
     * Collects data.
     *
     * @param MvcEvent $mvcEvent
     */
    public function collect(MvcEvent $mvcEvent)
    {
        return;
    }

}
