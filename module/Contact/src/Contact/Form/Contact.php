<?php

namespace Contact\Form;

use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Form;

class Contact extends Form implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('contact');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => 'Subject:'
            ),
            'attributes' => array(
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name' => 'message',
            'options' => array(
                'label' => 'Message:'
            ),
            'attributes' => array(
                'type' => 'textarea'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'subject' => array(
                'required' => true,
            ),
            'message' => array(
                'required' => true,
            )
        );
    }

}