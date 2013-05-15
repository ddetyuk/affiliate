<?php

namespace Application\Entity;

/**
 * Description of AbstractEntity
 *
 * @author ddetyuk
 */
abstract class AbstractEntity
{

    public function __call($method, $arguments)
    {
        if (0 === strpos($method, 'set') && isset($arguments[0])) {
            $param = lcfirst(substr($method, 3));
            $this->$param = $arguments[0];
            return $this;
        } else if (0 === strpos($method, 'get')) {
            $param = lcfirst(substr($method, 3));
            return $this->$param;
        } else if (0 === strpos($method, 'add')) {
            $param = lcfirst(substr($method, 3));
            if (is_array($this->$param)) {
                array_push($this->$param, $arguments[0]);
                return $this;
            }
            return false;
        }
        return false;
    }
    
    public function populate($data)
    {
        foreach ($data as $key => $value) {
            $this->{'set' . ucfirst($key)}($value);
        }
        return $this;
    }

    public function __construct($data = null)
    {
        if (!is_null($data)) {
            $this->exchangeArray($data);
        }
    }

}
