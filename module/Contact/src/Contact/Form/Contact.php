<?php

namespace Contact\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Form;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;


class Contact extends Form implements FactoryInterface, InputFilterProviderInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineEntity($em, 'Contact\Model\Entity\Message', false));
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
            'name' => 'subject',
            'type' => 'text',
            'options' => array(
                'label' => 'Subject:'
            ),
        ));
        $this->add(array(
            'name' => 'message',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Message:'
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Submit'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'subject' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
            ),
            'message' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
            )
        );
    }

}