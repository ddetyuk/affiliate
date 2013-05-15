<?php

namespace Application\Service;

use Doctrine\Common\Collections\ArrayCollection;

class Result
{

    const FAILURE = 0;
    const SUCCESS = 1;

    protected $code;
    protected $entity;
    protected $collection;
    protected $messages;

    public function __construct($code, $entity = null, array $messages = array())
    {
        $this->code = $code;

        if ($entity instanceof ArrayCollection) {
            $this->collection = $entity;
        } else {
            $this->entity = $entity;
        }

        $this->messages = $messages;
    }

    public function isSuccess()
    {
        return ($this->code > 0) ? true : false;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getMessages()
    {
        return $this->messages;
    }

}
