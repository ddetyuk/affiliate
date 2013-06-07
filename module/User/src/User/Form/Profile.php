<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Profile extends Form implements FactoryInterface, InputFilterProviderInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return $this;
    }

    public function __construct()
    {
        parent::__construct('profile');

        $this->setLabel('Profile');

        $this->add(array(
            'name'    => 'firstname',
            'options' => array(
                'label'      => 'First name:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name'    => 'lastname',
            'options' => array(
                'label'      => 'Last name:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name'    => 'password_old',
            'options' => array(
                'label'      => 'Password:'
            ),
            'attributes' => array(
                'type' => 'password'
            )
        ));
        $this->add(array(
            'name'    => 'password',
            'options' => array(
                'label'      => 'New Password:'
            ),
            'attributes' => array(
                'type' => 'password'
            )
        ));

        $this->add(array(
            'name'    => 'password_retype',
            'options' => array(
                'label'      => 'Confirm New Password:'
            ),
            'attributes' => array(
                'type' => 'password'
            )
        ));

        $this->add(array(
            'name'       => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
            )
        ));
    }

    /**
     * Define InputFilterSpecifications
     *
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'firstname' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                    array('name'     => 'StripTags')
                ),
            ),
            'lastname' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                    array('name'         => 'StripTags')
                ),
            ),
            'password_old' => array(
                'required'   => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 32
                        ),
                    ),
                )
            ),
            'password' => array(
                'required'   => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding'        => 'UTF-8',
                            'min'             => 6,
                            'max'             => 32
                        ),
                    ),
                )
            ),
            'password_retype' => array(
                'required'   => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 32
                        ),
                    ),
                    array(
                        'name'    => 'Identical',
                        'options' => array(
                            'token' => 'password'
                        )
                    )
                )
            ),
        );
    }

}