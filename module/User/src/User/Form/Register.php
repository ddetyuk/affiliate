<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Entity\User;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class Register extends Form implements FactoryInterface, InputFilterProviderInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineObject($em, 'User\Entity\User'));
        $this->setObject(new User());

        return $this;
    }

    public function __construct()
    {
        parent::__construct('login');

        $this->setLabel('Register');

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));

        $this->add(array(
            'name' => 'password_retype',
            'options' => array(
                'label' => 'Confirm Password:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name' => 'referal',
            'options' => array(
                'label' => 'Referal Code:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Register',
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
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                    ),
                ),
                'properties' => array(
                    'required' => true
                )
            ),
            'password' => array(
                'required' => true,
                'properties' => array(
                    'required' => true
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 32
                        ),
                    ),
                )
            ),
            'password_retype' => array(
                'required' => true,
                'properties' => array(
                    'required' => true
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 32
                        ),
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password'
                        )
                    )
                )
            ),
            'referal' => array(
                'required' => true,
                'properties' => array(
                    'required' => true
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                    ),
                ),
            )
        );
    }

}