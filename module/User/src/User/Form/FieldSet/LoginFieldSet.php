<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 26/07/2018
 * Time: 18:06
 */

namespace User\Form\FieldSet;


use User\Entity\User;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class LoginFieldSet extends Fieldset
{

    public function init()
    {
        $this->setHydrator(new ClassMethods());
        $this->setObject(new User());

        $email = new Element\Text('email');
        $email->setLabel('Email');
        $email->setAttribute('class', 'form-control');

        $password = new Element\Password('password');
        $password->setLabel('Password');
        $password->setAttribute('class', 'form-control');

        $this->add($email);
        $this->add($password);
    }
}