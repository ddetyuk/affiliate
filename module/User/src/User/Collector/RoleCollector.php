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

    public function __construct(Authorization $authorization)
    {
        
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
        
    }

}
