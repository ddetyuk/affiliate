<?php

namespace Account\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Form;

class Setting extends Form implements FactoryInterface, InputFilterProviderInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return $this;
    }

    public function __construct()
    {
        parent::__construct('contact');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'csrf',
            'type' => 'csrf',
        ));

        $this->add(array(
            'name'    => 'level1',
            'type'    => 'text',
            'options' => array(
                'label' => 'Repayment Level 1 (%):'
            ),
        ));
        $this->add(array(
            'name'    => 'level2',
            'type'    => 'text',
            'options' => array(
                'label' => 'Repayment Level 2 (%):'
            ),
        ));
        $this->add(array(
            'name'    => 'level3',
            'type'    => 'text',
            'options' => array(
                'label' => 'Repayment Level 3 (%):'
            ),
        ));
        $this->add(array(
            'name'    => 'minval',
            'type'    => 'text',
            'options' => array(
                'label' => 'Min Buffer value (%):'
            ),
        ));
        $this->add(array(
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => array(
                'value' => 'Save'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'level1' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                    array('name'   => 'StripTags')
                ),
            ),
            'level2' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                    array('name'   => 'StripTags')
                ),
            ),
            'level3' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
            ),
            'minval' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
            ),
        );
    }

}