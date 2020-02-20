<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 26/07/2018
 * Time: 18:20
 */

namespace User\Form;



use User\Entity\User;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class LoginForm extends Form
{

    public function __construct($name = null, array $options = array())
    {
        parent::__construct('login');
        $this->setHydrator(new ClassMethods(true));
        $this->setObject(new User());



       $this->add(array(
            'name' => 'login_field_set',
            'type' => 'User\Form\FieldSet\LoginFieldSet',
            'options' => array(
                'use_as_base_fieldset' => true,
                'label'=> false
            )
        ));

        $submit = new \Zend\Form\Element\Submit('submit');
        $submit->setValue('sign in');
        $submit->setAttribute('class', 'btn btn-primary');



        $this->add($submit);
    }
}