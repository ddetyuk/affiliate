<?php

namespace Application\Service;

class Result {

    const FAILURE = 0;
    const SUCCESS = 1;

    protected $code;
    protected $entity;
    protected $messages;

    public function __construct($code, $entity, array $messages = array())
    {
        $this->code = $code;
        $this->entity = $entity;
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

    public function getMessages()
    {
        return $this->messages;
    }

}
