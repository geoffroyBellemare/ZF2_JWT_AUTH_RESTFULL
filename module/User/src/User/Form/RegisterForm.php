<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 24/07/2018
 * Time: 15:01
 */

namespace User\Form;


use User\Entity\User;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class RegisterForm extends Form
{

    public function __construct($name = null, array $options = array())
    {
        parent::__construct('register');





    }
    public function init()
    {
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new User());
        //$this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'register_fieldset',
            'type' => 'User\Form\RegisterFieldSet',
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